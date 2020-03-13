<?php
header('content-type: text/html; charset=utf-8');
/* ---------- PRODUCTPRICEINFO FILE ---------- */
$csvname = "fichier.csv";
$filename    = $pathToWriteFile . $csvname;
$dateTime = date("o")."-".date("m")."-".date("d")." ".date("H").":".date("i");
// START Carico il file tramite AJAX
srand((double)microtime() * 1000000);
$rand = rand(0, 9999999999999999);
$rand = str_pad($rand, 14, "0", STR_PAD_BOTH);

$handle = fopen($filename, "r");
$fileSize = filesize($filename);
$fileContent = fread($handle, $fileSize);
$parsedFileContent = str_replace("  ", ";", $fileContent);
file_put_contents($filename, $parsedFileContent);
$fileSize = filesize($filename);
fclose($handle);

$date=new DateTime();
$dateFormat=$date->format('Ymd-Hi');
$newname="FLUX001_".$dateFormat.".csv";
$ligne=array();

if (file_exists($filename)) {
    echo "Le fichier $filename existe. Debut de la synchro<br/>";
} else {
    echo "Le fichier $filename n'a pas ete synchronise";
    die();
}

//sépare les données et crée les colonnes
$donnees=array();
$row = 1;
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 500000, ";")) !== FALSE) {
        $donnees[]=$data;
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            //echo $data[$c];
        }
    }
    fclose($handle);
}
// Database infos
$DBhost = 'localhost';
$DBowner = 'root';
$DBpw = '';
$DBName = 'meteo';

if ($DBPort == "") {
    $DBconnect = "mysql:dbname=".$DBName.";host=".$DBhost;
}
else {
// OVH sql server
    $DBconnect = "mysql:dbname=".$DBName.";host=".$DBhost.";port=".$DBPort;
}

echo " \$DBconnect = '<b>" .$DBconnect. "</b>' <br>";
echo "<br> \n";

try {
    // *************************************************************

    // *   ODBC base connnexion avec with driver invocation   *

    // *************************************************************

    echo " <font color=blue><b> Etablissement de la connexion SQL en mode PDO </b></font> <br>  <br>";
    $pdo = new PDO($DBconnect, $DBowner, $DBpw);
    $pdo->exec('SET NAMES utf8');
    echo " new PDO = <b>OK</b> <br> \n";
    $max = count($donnees)-1;
    echo $donnees[$max][0];
    $reqUpdateTemp = $pdo->prepare("INSERT INTO mesuretemperature (date, mesure) VALUES (:dateTime, :valeur)");
    $reqUpdateTemp->bindParam(':dateTime', $dateTime);
    $reqUpdateTemp->bindParam(':valeur', $donnees[$max][0]);
    $reqUpdateTemp->execute();

    $reqUpdateHum = $pdo->prepare("INSERT INTO mesurehumidite (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdateHum->bindParam(':dateTime', $dateTime);
    $reqUpdateHum->bindParam(':valeur', $donnees[$max][1]);
    $reqUpdateHum->execute();

    $reqUpdateLum = $pdo->prepare("INSERT INTO mesureluminosite (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdateLum->bindParam(':dateTime', $dateTime);
    $reqUpdateLum->bindParam(':valeur', $donnees[$max][2]);
    $reqUpdateLum->execute();

    $reqUpdatePres = $pdo->prepare("INSERT INTO mesurepression (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdatePres->bindParam(':dateTime', $dateTime);
    $reqUpdatePres->bindParam(':valeur', $donnees[$max][3]);
    $reqUpdatePres->execute();

    $reqUpdatePrec = $pdo->prepare("INSERT INTO mesureprecipitation (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdatePrec ->bindParam(':dateTime', $dateTime);
    $reqUpdatePrec ->bindParam(':valeur', $donnees[$max][4]);
    $reqUpdatePrec ->execute();

    $reqUpdateVent = $pdo->prepare("INSERT INTO mesurevent (date, valeur, valeur2) VALUES (:dateTime, :valeur, :valeur2)");
    $reqUpdateVent ->bindParam(':dateTime', $dateTime);
    $reqUpdateVent ->bindParam(':valeur', $donnees[$max][5]);
    $reqUpdateVent ->bindParam(':valeur2', $donnees[$max][6]);
    $reqUpdateVent ->execute();
    // Fermeture de la connexion
    echo " <b> Fermeture de la connexion SQL en mode PDO </b> <br>  <br>";
    $pdo = null;
}
catch(PDOException $e) {
    echo "Connexion échouée : <font color=red><b>" . $e->getMessage()."</b></font> <br> \n";
    echo "<br>";  // Ligne de séparation
}