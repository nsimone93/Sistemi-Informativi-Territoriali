<?php
	require_once('./connect.php');
	$query = pg_query("SELECT MAX(id) AS max FROM segnalazione;");
	$output = pg_fetch_array($query, 0);
	$id = $output['max'] + 1;
	$data = date('Y-m-d');
	$latlon = explode(",", $_POST['coord']);
	$point = "POINT(".$latlon[1]." ".$latlon[0].")";

	if ($_FILES['photo']['tmp_name'] == "") {
		$query = pg_query("INSERT INTO segnalazione (id, data, risolto, tipologia, descrizione, fotografia, geometry) VALUES ($id, '".$data."', False, '".$_POST['type']."', '".$_POST['desc']."', '', '".$point."');");
	}
	else {
	  $userfile_tmp = $_FILES['photo']['tmp_name'];
	  // Recupero il nome originale del file caricato
	  $userfile_name = "img_".date('Y-m-d')."_".time();
	  // Copio il file dalla sua posizione temporanea alla mia cartella upload
	  move_uploaded_file($userfile_tmp, "/opt/lampp/htdocs/2019/GIT/upload/".$userfile_name);
	  $query = pg_query("INSERT INTO segnalazione (id, data, risolto, tipologia, descrizione, fotografia, geometry) VALUES ($id, '".$data."', False, '".$_POST['type']."', '".$_POST['desc']."', '".$userfile_name."', '".$point."');");
	}
?>
<html>
	<body>
		<form id = "go" action = "./signal.php" method = "POST">
	    	<input type = "text" value = "0" name = "check" id = "check">
	  	</form>
	</body>
	<script>
		document.getElementById('go').submit();
	</script>
</html>
