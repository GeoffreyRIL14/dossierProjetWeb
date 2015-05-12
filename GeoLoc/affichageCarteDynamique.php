<!doctype html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>

function affichercarte() {
	
	var options = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	 
	var carte = new google.maps.Map(document.getElementById("carte"), options);
	
	var marker;
	
	<?php
$con=mysqli_connect("localhost","root","","bdd");
if (mysqli_connect_errno($con))
  {
  echo "erreur connexion mysql: ".mysqli_connect_error();
  }

$sql="SELECT latitude, longitude,nom,dateUpload FROM Images";
$result=mysqli_query($con,$sql);


while($ligne=mysqli_fetch_array($result,MYSQLI_NUM)){
	$latitude=$ligne[0];
	$longitude=$ligne[1];
	$title=$ligne[2]." téléchargée le ".$ligne[3];
	echo "marker = new google.maps.Marker({ position: new google.maps.LatLng(".$latitude.", ".$longitude."), title:'".$title."'});
	marker.setMap(carte)";
	echo "carte.panTo(new google.maps.LatLng(".$latitude.", ".$longitude.")";
}

mysqli_free_result($result);
mysqli_close($con);
?>

			
}
            


</script>
</head>

<body onload="affichercarte();">

<div id="carte" style="height: 800px;width:800px"></div>


</body>
</html>
