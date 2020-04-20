#!/usr/bin/php
<?php
define( 'DEBUG', 0);

/* ---------- PRODUCTPRICEINFO FILE ---------- */
$pathToWriteFile = dirname( __FILE__) . '/';
$csvname = "meteo.csv";
$filename    = $pathToWriteFile . $csvname;
$dateTime = date("o")."-".date("m")."-".date("d")." ".date("H").":".date("i");
// START Carico il file tramite AJAX
//srand((double)microtime() * 1000000);
//$rand = rand(0, 9999999999999999);
//$rand = str_pad($rand, 14, "0", STR_PAD_BOTH);

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
    if ( DEBUG) echo "Le fichier $filename existe. Debut de la synchro<br/>";
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
$DBPort = null;

$DBconnect = "mysql:dbname=".$DBName.";host=".$DBhost;
if ( is_int( $DBPort)) {
// OVH sql server
    $DBconnect .= ";port=".$DBPort;
}

if ( DEBUG) echo " \$DBconnect = '<b>" .$DBconnect. "</b><br>";

try {
    // *************************************************************

    // *  PDO base connnexion avec with driver invocation   *

    // *************************************************************

    if ( DEBUG) echo " <font color=blue><b> Etablissement de la connexion SQL en mode PDO </b></font> <br>  <br>";
    $pdo = new PDO($DBconnect, $DBowner, $DBpw);
    $pdo->exec('SET NAMES utf8');
    if ( DEBUG) echo " new PDO = <b>OK</b> <br> \n";
    $reqUpdateTemp = $pdo->prepare("INSERT INTO mesuretemperature (date, mesure) VALUES (:dateTime, :valeur)");
    $reqUpdateTemp->bindParam(':dateTime', $dateTime);
    $reqUpdateTemp->bindParam(':valeur', $donnees[1][0]);
    var_dump($donnees[1][0]);
    if(!empty($donnees[1][0])){
    	$reqUpdateTemp->execute();
    }

    $reqUpdateHum = $pdo->prepare("INSERT INTO mesurehumidite (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdateHum->bindParam(':dateTime', $dateTime);
    $reqUpdateHum->bindParam(':valeur', $donnees[1][1]);
    $reqUpdateHum->execute();

    $reqUpdateLum = $pdo->prepare("INSERT INTO mesureluminosite (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdateLum->bindParam(':dateTime', $dateTime);
    $reqUpdateLum->bindParam(':valeur', $donnees[1][2]);
    if(!empty($donnees[1][2])){
    $reqUpdateLum->execute();
    }

    $reqUpdatePres = $pdo->prepare("INSERT INTO mesurepression (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdatePres->bindParam(':dateTime', $dateTime);
    $reqUpdatePres->bindParam(':valeur', $donnees[1][3]);
    if(!empty($donnees[1][3])){
    	$reqUpdatePres->execute();
    }

    $reqUpdatePrec = $pdo->prepare("INSERT INTO mesureprecipitation (date, valeur) VALUES (:dateTime, :valeur)");
    $reqUpdatePrec ->bindParam(':dateTime', $dateTime);
    $reqUpdatePrec ->bindParam(':valeur', $donnees[1][4]);
    if(!empty($donnees[1][4])){
    	$reqUpdatePrec ->execute();
    }
    $reqUpdateVent = $pdo->prepare("INSERT INTO mesurevent (date, valeur, valeur2) VALUES (:dateTime, :valeur, :valeur2)");
    $reqUpdateVent ->bindParam(':dateTime', $dateTime);
    $reqUpdateVent ->bindParam(':valeur', $donnees[1][5]);
    $reqUpdateVent ->bindParam(':valeur2', $donnees[1][6]);
    if(!empty($donnees[1][5])){
    	$reqUpdateVent ->execute();
    }

    $rerqDeleteOldValues = $pdo->prepare("DELETE FROM mesuretemperature WHERE date < DATE( NOW() ) AND TIME(date) != '00:00:00' AND TIME(date) != '06:00:00' AND TIME(date) != '12:00:00' AND TIME(date) != '18:00:00';
    DELETE FROM mesurehumidite WHERE date < DATE( NOW() ) AND TIME(date) != '00:00:00' AND TIME(date) != '06:00:00' AND TIME(date) != '12:00:00' AND TIME(date) != '18:00:00';
    DELETE FROM mesureluminosite WHERE date < DATE( NOW() ) AND TIME(date) != '00:00:00' AND TIME(date) != '06:00:00' AND TIME(date) != '12:00:00' AND TIME(date) != '18:00:00';
    DELETE FROM mesurepression WHERE date < DATE( NOW() ) AND TIME(date) != '00:00:00' AND TIME(date) != '06:00:00' AND TIME(date) != '12:00:00' AND TIME(date) != '18:00:00';
    DELETE FROM mesureprecipitation WHERE date < DATE( NOW() ) AND TIME(date) != '00:00:00' AND TIME(date) != '06:00:00' AND TIME(date) != '12:00:00' AND TIME(date) != '18:00:00';
    DELETE FROM mesurevent WHERE date < DATE( NOW() ) AND TIME(date) != '00:00:00' AND TIME(date) != '06:00:00' AND TIME(date) != '12:00:00' AND TIME(date) != '18:00:00';");
    $rerqDeleteOldValues -> execute();
    // Fermeture de la connexion
    if ( DEBUG) echo " <b> Fermeture de la connexion SQL en mode PDO </b> <br>  <br>";
    $pdo = null;
}
catch(PDOException $e) {
    echo "Connexion échouée : <font color=red><b>" . $e->getMessage()."</b></font> <br> \n";
    echo "<br>";  // Ligne de séparation
}
