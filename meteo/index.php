<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<head>
	<meta charset="utf-8" />
	<link rel ="stylesheet" href="Style1.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<title> Météo </title>
</head>

<body style = "background:url('unnamed.jpg');background-size:cover;margin:0;">
    <div id="cont_0f9eeeec2f5492815f8d13e7134b7a42"><img src="https://www.tameteo.com/wimages/fotoc44b8663d3a847df9a8cb2e3542be346.png" style = "margin-left:15%;margin-right:15%;"></div>
	<div class="requete" style="margin-left: 15%; margin-right: 15%;"></br>
		<form name="text" method="POST" action="" style="color: white;">
			Entrez une date de début : <input class="form-control" type="date" name="date_debut" /></br>
			Entrez une date de fin : <input type="date" class="form-control" name="date2" /></br>
			<SELECT class="form-control" name="selection" size="1">
					<OPTION>Température
					<OPTION>Humidité
					<OPTION>Luminosité
					<OPTION>Pression
					<OPTION>Précipitations
					<OPTION>Vent
				</SELECT></br>
			<input type="submit" class="btn btn-secondary btn-lg" name="valider" value="Voir les relevés"/>
		</form>
	</div>
</body>

<?php
$jsondate2="";
$jsontemp2="";
$bdd = new PDO('mysql:host=localhost;dbname=meteo;charset=utf8', 'root', 'password');
if (isset($_POST['valider']) && !empty($_POST['date_debut']) && !empty($_POST['date2']))
{
	$date_debut = $_POST['date_debut']." 0:00";
	$date2 = $_POST['date2']." 23:59";
	$selection = $_POST['selection'];
	$tot=0;
	$count = 0;

	if($selection == "Température"){

		$reqAfficherTemperature = $bdd->prepare("SELECT * FROM mesuretemperature WHERE date >= :date1 AND date <= :date2");
        $reqAfficherTemperature->bindParam(':date1', $date_debut);
        $reqAfficherTemperature->bindParam(':date2', $date2);
		$reqAfficherTemperature->execute();
	?>
		<div class="profile" style="width: 70%; margin-left: 15%;">
			<table class="table" style="color: white;">
			<tr>
				<td><h4>Date</h4></td>
				<td><h4>Heure</h4></td>
				<td><h4>Température</h4></td>
			</tr>
					
	<?php
        $temperature = $temperature=$reqAfficherTemperature->fetch();
        while($temperature=$reqAfficherTemperature->fetch())
		{	
            $jsondate[] = substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4)." ".substr($temperature["date"],11, -10);
            $jsontemp[] = $temperature["mesure"];
            //var_dump(json_encode($jsondate, JSON_NUMERIC_CHECK));			
	?>
				<tr>
				<td><?php echo substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4);?></td>
				<td><?php echo substr($temperature["date"],11, -10)?></td>
                    <td><?php if($temperature["mesure"]<32) {echo $temperature["mesure"]." °C";}else {echo $temperature["mesure"]." °C";?> <img src="chaleur_warning.png"> <?php } ?></td>
				</tr>					
	<?php
            $count ++;
            $tot+=$temperature["mesure"];
        }
        if(!empty($jsondate)){
            $jsondate2 = json_encode($jsondate, JSON_NUMERIC_CHECK);
        }
        if(!empty($jsontemp)){
            $jsontemp2 = json_encode($jsontemp, JSON_NUMERIC_CHECK);
        }
		if(!empty($count)) {
            $moyenne = $tot / $count;
        }
		else{
		    $moyenne="--";
        }
	?>
                <h2 style="color: white;">température moyenne: <?php echo substr($moyenne, 0, 4);?> °C</h2>
		</div>
        <div class="profile"style = "color: white;">
            <canvas id="myChart"></canvas>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: <?php echo $jsondate2 ?>,
                        datasets: [{
                            label: 'évolution de la température',
                            backgroundColor: 'rgb(182, 128, 141)',
                            borderColor: 'rgb(255, 255, 255)',
                            data: <?php echo $jsontemp2 ?>
                        }]
                    },

                    // Configuration options go here
                    options: {}
                });
            </script>
        </div>
	<?php
		}
	else if($selection == "Humidité"){
		
		$reqAfficherTemperature = $bdd->prepare("SELECT * FROM mesurehumidite WHERE date >= :date1 AND date <= :date2");
		$reqAfficherTemperature->bindParam(':date1', $date_debut);
		$reqAfficherTemperature->bindParam(':date2', $date2);
		$reqAfficherTemperature->execute();
	?>
		<div class="profile" style="width: 70%; margin-left: 15%;">
			<table class="table" style="color: white;">
			<tr>
				<td><h4>Date</h4></td>
				<td><h4>Heure</h4></td>
				<td><h4>Humidité</h4></td>
			</tr>
					
	<?php
		while($temperature=$reqAfficherTemperature->fetch())
		{
            $jsondate[] = substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4)." ".substr($temperature["date"],11, -10);
            $jsontemp[] = $temperature["valeur"];							
	?>
				<tr>
                    <td><?php echo substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4);?></td>
				<td><?php echo substr($temperature["date"],11, -10)?></td>
				<td><?php echo $temperature["valeur"]." %";?></td>
				</tr>
    <?php
            $count ++;
            $tot+=$temperature["valeur"];
        }
        if(!empty($jsondate)){
            $jsondate2 = json_encode($jsondate, JSON_NUMERIC_CHECK);
        }
        if(!empty($jsontemp)){
            $jsontemp2 = json_encode($jsontemp, JSON_NUMERIC_CHECK);
        }
    if(!empty($count)) {
        $moyenne = $tot / $count;
    }
    else{
        $moyenne="--";
    }
    ?>
                <h2 style="color: white;">Humidité moyenne: <?php echo substr($moyenne, 0, 4);?> %</h2>
		</div>
        <div class="profile"style = "color: white;">
            <canvas id="myChart"></canvas>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: <?php echo $jsondate2 ?>,
                        datasets: [{
                            label: "évolution de l'humidité",
                            backgroundColor: 'rgb(182, 128, 141)',
                            borderColor: 'rgb(255, 255, 255)',
                            data: <?php echo $jsontemp2 ?>
                        }]
                    },

                    // Configuration options go here
                    options: {}
                });
            </script>
        </div>
	<?php
	}
    else if($selection == "Luminosité"){

        $reqAfficherTemperature = $bdd->prepare("SELECT * FROM mesureluminosite WHERE date >= :date1 AND date <= :date2");
        $reqAfficherTemperature->bindParam(':date1', $date_debut);
        $reqAfficherTemperature->bindParam(':date2', $date2);
        $reqAfficherTemperature->execute();
        ?>
        <div class="profile" style="width: 70%; margin-left: 15%;">
            <table class="table" style="color: white;">
                <tr>
                    <td><h4>Date</h4></td>
                    <td><h4>Heure</h4></td>
                    <td><h4>Luminosité</h4></td>
                </tr>

                <?php
                while($temperature=$reqAfficherTemperature->fetch())
                {
                    ?>
                    <tr>
                        <td><?php echo substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4);?></td>
                        <td><?php echo substr($temperature["date"],11, -10)?></td>
                        <td><?php echo $temperature["valeur"]." "; if($temperature["valeur"]>700) echo "(jour)"; else echo"(nuit)";?></td>
                    </tr>
                    <?php
                }
                ?>
        </div>
        <?php
    }
    else if($selection == "Pression"){

        $reqAfficherTemperature = $bdd->prepare("SELECT * FROM mesurepression WHERE date >= :date1 AND date <= :date2");
        $reqAfficherTemperature->bindParam(':date1', $date_debut);
        $reqAfficherTemperature->bindParam(':date2', $date2);
        $reqAfficherTemperature->execute();
        ?>
        <div class="profile" style="width: 70%; margin-left: 15%;">
            <table class="table" style="color: white;">
                <tr>
                    <td><h4>Date</h4></td>
                    <td><h4>Heure</h4></td>
                    <td><h4>Pression</h4></td>
                </tr>

                <?php
                while($temperature=$reqAfficherTemperature->fetch())
                {
                    $jsondate[] = substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4)." ".substr($temperature["date"],11, -10);
                    $jsontemp[] = $temperature["valeur"];
                    ?>
                    <tr>
                        <td><?php echo substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4);?></td>
                        <td><?php echo substr($temperature["date"],11, -10)?></td>
                        <td><?php if($temperature["valeur"]>1000) {echo $temperature["valeur"]." Hpa";}else {echo $temperature["valeur"]." Hpa";?> <img src="temps_warning.png"> <?php } ?></td>
                    </tr>
                    <?php
                    $count ++;
                    $tot+=$temperature["valeur"];
                }
                if(!empty($jsondate)){
                    $jsondate2 = json_encode($jsondate, JSON_NUMERIC_CHECK);
                }
                if(!empty($jsontemp)){
                    $jsontemp2 = json_encode($jsontemp, JSON_NUMERIC_CHECK);
                }
                if(!empty($count)) {
                $moyenne = $tot / $count;
    }
    else{
        $moyenne="--";
    }
                ?>
                <h2 style="color: white;">Pression moyenne: <?php echo substr($moyenne, 0, 4);?> Hpa</h2>
        </div>
        <div class="profile"style = "color: white;">
            <canvas id="myChart"></canvas>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: <?php echo $jsondate2 ?>,
                        datasets: [{
                            label: 'évolution de le pression',
                            backgroundColor: 'rgb(182, 128, 141)',
                            borderColor: 'rgb(255, 255, 255)',
                            data: <?php echo $jsontemp2 ?>
                        }]
                    },

                    // Configuration options go here
                    options: {}
                });
            </script>
        </div>
        <?php
    }
    else if($selection == "Précipitations"){

        $reqAfficherTemperature = $bdd->prepare("SELECT * FROM mesureprecipitation WHERE date >= :date1 AND date <= :date2");
        $reqAfficherTemperature->bindParam(':date1', $date_debut);
        $reqAfficherTemperature->bindParam(':date2', $date2);
        $reqAfficherTemperature->execute();
        ?>
        <div class="profile" style="width: 70%; margin-left: 15%;">
            <table class="table" style="color: white;">
                <tr>
                    <td><h4>Date</h4></td>
                    <td><h4>Heure</h4></td>
                    <td><h4>Précipitations</h4></td>
                </tr>

                <?php
                while($temperature=$reqAfficherTemperature->fetch())
                {
                    $jsondate[] = substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4)." ".substr($temperature["date"],11, -10);
                    $jsontemp[] = $temperature["valeur"];
                    ?>
                    <tr>
                        <td><?php echo substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4);?></td>
                        <td><?php echo substr($temperature["date"],11, -10)?></td>
                        <td><?php echo $temperature["valeur"]." mm";?></td>
                    </tr>
                    <?php
                    $count ++;
                    $tot+=$temperature["valeur"];
                    if(!empty($jsondate)){
                        $jsondate2 = json_encode($jsondate, JSON_NUMERIC_CHECK);
                    }
                    if(!empty($jsontemp)){
                        $jsontemp2 = json_encode($jsontemp, JSON_NUMERIC_CHECK);
                    }
                }
                ?>
                <h2 style="color: white;">Cumuls de précipitations: <?php echo $tot;?> mm</h2>
        </div>
        <div class="profile"style = "color: white;">
            <canvas id="myChart"></canvas>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: <?php echo $jsondate2 ?>,
                        datasets: [{
                            label: 'évolution de le pression',
                            backgroundColor: 'rgb(182, 128, 141)',
                            borderColor: 'rgb(255, 255, 255)',
                            data: <?php echo $jsontemp2 ?>
                        }]
                    },

                    // Configuration options go here
                    options: {}
                });
            </script>
        </div>
        <?php
    }
    else{

        $reqAfficherTemperature = $bdd->prepare("SELECT * FROM mesurevent WHERE date >= :date1 AND date <= :date2");
        $reqAfficherTemperature->bindParam(':date1', $date_debut);
        $reqAfficherTemperature->bindParam(':date2', $date2);
        $reqAfficherTemperature->execute();
        ?>
        <div class="profile" style="width: 70%; margin-left: 15%;">
            <table class="table" style="color: white;">
                <tr>
                    <td><h4>Date</h4></td>
                    <td><h4>Heure</h4></td>
                    <td><h4>Vitesse du vent</h4></td>
                    <td><h4>Orientation du vent</h4></td>
                </tr>

                <?php
                while($temperature=$reqAfficherTemperature->fetch())
                {
                    ?>
                    <tr>
                        <td><?php echo substr($temperature["date"],8, 2)."/".substr($temperature["date"],5, 2)."/".substr($temperature["date"],0, 4);?></td>
                        <td><?php echo substr($temperature["date"],11, -10)?></td>
                        <td><?php if($temperature["valeur"]<100) {echo $temperature["valeur"]." km/h";}else {echo $temperature["valeur"]." km/h";?> <img src="vent_warning.png"> <?php } ?></td>
                        <td><?php echo $temperature["valeur2"];?></td>
                    </tr>
                    <?php
                    $count ++;
                    $tot+=$temperature["valeur"];
                }
                if(!empty($count)) {
                    $moyenne = $tot / $count;
                }
                else{
                    $moyenne="--";
                }
                ?>
                <h2 style="color: white;">Vitesse moyenne: <?php echo substr($moyenne, 0, 4);?> Km/h</h2>
        </div>
        <?php
    }
}
?>

