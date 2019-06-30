<!DOCTYPE html>
<html lang="en">
	<head>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  	<meta name="description" content="">
	  	<meta name="author" content="">
	  	<!-- Bootstrap Core CSS -->
		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom Fonts -->
		<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
		<link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
		<!-- Custom CSS -->
		<link href="css/stylish-portfolio.min.css" rel="stylesheet">
		<!--Map -->
		<link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
		<link rel="stylesheet" type="text/css" href="leaflet/leaflet.css" />
		<script type="text/javascript" src="leaflet/leaflet.js"></script>
		<script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.4.3/proj4.js"></script>
		<script src="http://epsg.io/3003.js"></script>
		<script src="http://epsg.io/4326.js"></script>
	</head>

	<body id="page-top">
  	<?php
  		require_once('./connect.php');
  		if (!$_GET) {
  			if (isset($_POST['check'])) {
  				echo "<script>
  					alert(\"Inserimento avvenuto con successo\");
  				</script>";
  			}
  	?>

		<!-- Navigation -->
		<a class="menu-toggle rounded" href="#">
			<i class="fas fa-bars"></i>
		</a>
		<nav id="sidebar-wrapper" style="z-index: 100;">
		    <ul class="sidebar-nav">
		      	<li class="sidebar-brand">
		        	<a class="js-scroll-trigger" href="#page-top">RETE IDRICA</a>
		      	</li>
		      	<li class="sidebar-nav-item">
			        <a class="js-scroll-trigger" href="./login.php">Logout</a>
			    </li>
		    </ul>
		</nav>

	  	<!-- Header -->
		<title>Rete idrica</title>
		<center>
		  	<div style="width:100%; padding-top:25px; padding-bottom:25px; background-color: #1d809f;">
		    	<h1 style="color: white;">Invia segnalazione</h1>
		  	</div>
		</center>
		  
		<header class="masthead d-flex" style="backgroung-color: blue; padding-top: 20px;">
		    <h3 class="mb-5" style="font-size: 20px;">
	          	<center>
	            	<table  style="margin-left:150px; width: 100%;">
	              		<tr>
	                		<td>
	                    		<form id="go" name="go" method="POST" enctype="multipart/form-data">
	                    			<table  style="margin-left: 25px;">
	                        			<tr>
	                          				<td></td>
	                          				<td style="font-weight: normal; padding-bottom:20px;">Selezionare un punto sulla mappa</td>
	                        			</tr>
	                    				<tr>
	                    					<td>Tipologia*:</td>
	                    					<td>
	                    						<select id="type" name="type" style="width:100%;" >
		                    						<option selected="selected">Guasto</option>
		                    						<option>Perdita</option>
		                    						<option>Segnalazione</option>
	                    						</select>
	                    					</td>
	                    				</tr>
			                    		<tr>
			                    			<td>Descrizione:</td>
	        		            			<td><textarea style="font-size: 20px; width:100%;" id="desc" rows="5" type="text" name="desc"></textarea></td>
	                		    		</tr>
	                    				<tr>
	                    					<td>Fotografia:</td>
	                    					<td><input style="font-size: 20px;" id="photo" type="file" name="photo"/></td>
	                    				</tr>
	                    				<tr>
	                          				<td style="width:100%;"></td>
	                    					<td>
	                    						<input style="font-size: 20px; width:100%; margin-top:20px;" type="submit" class="btn btn-primary btn-xl js-scroll-trigger" value="Invia" onclick="check()"/>
	                    					</td>
	                    				</tr>
	                    				<tr>
	                    					<td></td>
	                    					<td>* Campo obbligatorio</td>
	                    				</tr>
	                    				<tr>
	                    					<td><input type="text" id="coord" name="coord" value="" style="font-size: 20px; display: none;"/></td>
	                    				</tr>
			                    	</table>
	                    		</form>
	      					</td>
	        				<td>
	        					<div id="mapid" style="width: 700px; height: 800px; float: right;">
	        					</div>
	        				</td>
	      				</tr>
	      			</table>
	    		</center>
		    </h3>
		    <div class="overlay"></div>
		</header>

		<?php
		}
		else {
		?>

	  	<!-- AMMINISSTRATORE -->
	  	<!-- Navigation -->
	  	<title>Rete idrica</title>
	  	<a class="menu-toggle rounded" href="#">
	    	<i class="fas fa-bars"></i>
	  	</a>
	  	<nav id="sidebar-wrapper" style="z-index: 100;">
	    	<ul class="sidebar-nav">
	      		<li class="sidebar-brand">
	        		<a class="js-scroll-trigger" href="#page-top">RETE IDRICA</a>
	      		</li>
	      		<li class="sidebar-nav-item">
	        		<a class="js-scroll-trigger" href="./login.php">Logout</a>
	      		</li>
	    	</ul>
	  	</nav>

	  	<center>
	    	<div style="width:100%; padding-top:25px; padding-bottom:25px; background-color: #1d809f;">
	      		<h1 style="color: white;">Visualizza segnalazioni</h1>
	    	</div>
	  	</center>
	  	<!-- Header -->
	  	<header class="masthead d-flex" style="backgroung-color: blue; padding-top: 20px;">
	    	<h3 class="mb-5" style="font-size: 20px; width:100%;">
	      		<table style="width:100%;">
	      			<tr>
	      				<td style="padding-left: 20px; padding-right: 20px; width: 47%; vertical-align: top;">
	        				<form action="./signal.php?sig=true" method="POST">
		          				<table style="width:100%;">
		          					<tr>
		          						<td>Anno:</td>
		          						<td>Tipologia:</td>
		          						<td></td>
		          					</tr>
		          					<tr>
		          						<td>
		          							<select id="year" name="year" style="width:90%;">
			<?php
				$query = pg_query("SELECT data FROM segnalazione ORDER BY data DESC;");
				$anno = 0;
				for ($i = 0; $i < pg_num_rows($query); $i++) {
					$output = pg_fetch_array($query, $i);
					$data = explode("-", $output['data'])[0];
					if ($data != $anno) {
						echo "<option>".$data."</option>";
						$anno = $data;
					}
				}
			?>
		          							</select>
		          						</td>
		          						<td>
		          							<select id="type" name="type" style="width:90%;">
		          								<option>Guasto</option>
		          								<option>Perdita</option>
		          								<option>Segnalazione</option>
		                          				<option selected="selected">Tutte</option>
		          							</select>
		          						</td>
		          						<td><input type="submit" class="btn btn-primary btn-xl js-scroll-trigger" value="Cerca"/></td>
		          					</tr>
		          				</table>
	        			   	</form>
	                 		<br>
			      			<div>

			<?php      					
			if (isset($_POST['year'])) {
				// Devo riempire la tabella
				$data_in = $_POST['year']."-01-01";
				$data_fin = $_POST['year']."-12-31";
          		if ($_POST['type'] == 'Tutte') {
					$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."'  ORDER BY data;");
          		}
          		else {
            		$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' ORDER BY data;");
          		}
				echo "<center>
						<table style=\"width: 100%; height: 100%; font-weight: normal; border: 1px solid #ddd;\">
			      			<tr>
      							<th style=\"border: 1px solid #ddd; width:4%; padding-left:5px;\">ID</th>
      							<th style=\"border: 1px solid #ddd; width:15%; padding-left:5px;\">Data</th>
      							<th style=\"border: 1px solid #ddd; width:16%; padding-left:5px;\">Tipologia</th>
      							<th style=\"border: 1px solid #ddd; width:50%; padding-left:5px;\">Descrizione</th>
			                    <th style=\"border: 1px solid #ddd; width:10%; padding-left:5px;\">Risolto</th>
			                    <th style=\"border: 1px solid #ddd; width:5%; padding-left:5px;\">Foto</th>
			      			</tr>";
				for ($i = 0; $i < pg_num_rows($query); $i++) {
					$output = pg_fetch_array($query, $i);
					echo "
					<tr>
						<td style=\"border: 1px solid #ddd; padding-left:5px;\">".$output['id']."</td>
						<td style=\"border: 1px solid #ddd; padding-left:5px;\">".$output['data']."</td>
						<td style=\"border: 1px solid #ddd; padding-left:5px;\">".$output['tipologia']."</td>
						<td style=\"border: 1px solid #ddd; padding-left:5px;\">".$output['descrizione']."</td>";
					if ($output['risolto'] === 't') {
						echo "<td style=\"border: 1px solid #ddd;\"><center><img src=\"./leaflet/images/marker-icon-green.png\" width=\"20px\" height=\"30px\"/></center></td>";
					}
					else {
						echo "<td style=\"border: 1px solid #ddd;\"><center><img src=\"./leaflet/images/marker-icon-red.png\" width=\"20px\" height=\"30px\"/></center></td>";
					}
					// Inserimento foto se presente
					if ($output['fotografia'] != "") {
				    	echo "<td id=\"".$i."\" onclick=\"openphoto(".$i.")\" style=\"border: 1px solid #ddd;\"><center><img src=\"./img/photo1.png\" width=\"20px\" height=\"20px\"/></center></td>";
				    }
    				else {
      					echo "<td style=\"border: 1px solid #ddd;\"><center><img src=\"./img/nophoto1.png\" width=\"20px\" height=\"20px\"/></center></td>";
    				}
    				echo "</tr>";
      				if ($output['fotografia'] != "") {
      					echo "<tr><td colspan=\"6\" style=\"border: 1px solid #ddd;\"> <div id = \"photo".$i."\" colspan=\"5\"><center><img src = \"./upload/".$output['fotografia']."\" style=\"width: 300px; height: 250px;\"></center></div><td></tr>";
    				}
    				else {
      					echo "<tr><td colspan=\"6\" style=\"border: 1px solid #ddd;\"> <div id = \"photo".$i."\" colspan=\"5\"><center></center></div><td></tr>";
    				}
				}

			    echo "</table>
			    </center>";
			    echo "<script>";
              	for ($k = 0; $k < pg_num_rows($query); $k++) {
                	$output = pg_fetch_array($query, $k);
                	if ($output['fotografia'] != "") {
                  		echo "document.getElementById('".$k."').value = 0;";
                  		echo "document.getElementById('photo".$k."').style.display = \"none\";";
                	}
              	}
                echo "</script>";
  			}
			?>

			      			</div>
	      				</td>
	      				<td style="width: 55%; vertical-align: top;">
	            			<table>
	              				<tr>
	                				<td>
	        			       			<div id="mapid" style="width: 700px; height: 800px; float: left;"></div>
	                				</td>	
	        			 			<td style="vertical-align: top;">
	        							<table style="width: 100%; margin-left: 5px;">
	        								<tr>
	        									<td><input type="checkbox" name="ru" id="ru" onclick="ru()" value ="0">Raccordi Utenze</td>
	        									<td><img src="./leaflet/images/marker-ru.png" style="width: 25px; height: 25px; margin-left:10px;"/></td>
	                  						</tr>
							                <tr>
	        									<td><input type="checkbox" name="poz" id="poz" onclick="poz()" value ="0">Pozzetti</td>
	        									<td><img src="./leaflet/images/marker-p.png" style="width: 25px; height: 25px; margin-left:10px;"/></td>
	                  						</tr>
	                  						<tr>
	        									<td><input type="checkbox" name="t" id="t" onclick="t()" value ="0">Tratte</td>
	        									<td><img src="./leaflet/images/line.png" style="width: 25px; height: 25px; margin-left:10px;"/></td>
	                  						</tr>
	                  						<tr>
	        									<td><input type="checkbox" name="ris" id="ris" onclick="ris()" value ="1" checked>Risolta</td>
	        									<td><img src="./leaflet/images/marker-icon-green.png" style="width: 20px; height: 30px; margin-left:10px;"/></td>
	                  						</tr>
	                  						<tr>
	        									<td><input type="checkbox" name="noris" id="noris" onclick="noris()" value ="1" checked>Non risolta</td>
	        									<td><img src="./leaflet/images/marker-icon-red.png" style="width: 20px; height: 30px; margin-left:10px;"/></td>
	        								</tr>
	        							</table>
	              					</td>
	            				</tr>
	      					</table>
	      				</td>
	      			</tr>
	      		</table>
	      	</h3>
	    	<div class="overlay"></div>
	  	</header>

	  	<?php
	  	}
	  	?>
		
	<!-- Bootstrap core JavaScript -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- Plugin JavaScript -->
	<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
	<!-- Custom scripts for this template -->
	<script src="js/stylish-portfolio.min.js"></script>

</body>


	<?php
	if (!$_GET) {
	  // Parte utente
	?>

	<script type="text/javascript">
		var map;
		
		function initmap() {
			// set up the map
			map = new L.Map('mapid');
			//map.L.Projection.SphericalMercator;
			// create the tile layer with correct attribution
			var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
			var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
			var osm = new L.TileLayer(osmUrl, {minZoom: 15, maxZoom: 19, attribution: osmAttrib});

			// start the map in South-East England
			map.setView(new L.LatLng(45.43455, 11.87281), 15);
			map.addLayer(osm);
		}

		initmap();
		var marker = null; //L.marker([45.5, 11.09]).addTo(map);
		var flag = 0;

		function onMapClick(e) {
			flag = 1;
			// Controllo la presenza del marker
			if (marker != null)
			{
				marker.remove();
			}
			// Creo un nuovo marker
			marker = L.marker(e.latlng).addTo(map);

			// Converto le coordinate da WGS84 a Gauss-Boaga fuso ovest
			var coord = ol.proj.transform([e.latlng.lng, e.latlng.lat], "EPSG:4326", "EPSG:3003");

			document.getElementById("coord").value = coord[1] + "," + coord[0];
		}

		map.on('click', onMapClick);

		function check() {
			// Controllo la presenza di un marker
			if (flag == 1) {
				// Invio alla pagina di inserimento
				document.getElementById('go').action = "./checksig.php";
				document.getElementById('go').submit();
			}
			else {
				// Messaggio per selezione coordinate sulla mappa
				alert("Punto sulla mappa non selezionato");
			}
		}

	</script>

	<?php
	}
	else {
	?>

	<script type="text/javascript">
	// Parte amministratore

	// Visualizzazione Fotografia
	function openphoto(i) {
		if (document.getElementById(i.toString()).value == 0) {
		    // Mostro la fotografia
		    document.getElementById(i.toString()).value = 1;
		    var name = ('photo' + i);
		    document.getElementById(name).style.display = "block";
		    document.getElementById(name).colSpan = "6";
	  	}
	  	else {
		    // Nascondo la fotografia
		    document.getElementById(i).value = 0;
		    document.getElementById('photo' + i.toString()).style.display = "none";
	  	}
	}

	// Inizzializzazione mappa
	document.getElementById("ris").checked = true;
	document.getElementById("noris").checked = true;
	var map;

		<?php

		$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM ru;");
		for ($i = 0; $i < pg_num_rows($query); $i++) {
			echo "var markerru".$i.";";
		}
		$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM pozzetto;");
		for ($i = 0; $i < pg_num_rows($query); $i++) {
			echo "var markerpoz".$i.";";
		}
		$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM tratta WHERE tipologia = True;");
		for ($i = 0; $i < pg_num_rows($query); $i++) {
			echo "var polyline".$i.";";
		}
		if (isset($_POST['year'])) {
		  	$data_in = $_POST['year']."-01-01";
		  	$data_fin = $_POST['year']."-12-31";
		  	$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = True ORDER BY data;");
		  	for ($i = 0; $i < pg_num_rows($query); $i++) {
		  		echo "var ris".$i.";";
		  	}
		  	$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = False ORDER BY data;");
		  	for ($i = 0; $i < pg_num_rows($query); $i++) {
		  		echo "var noris".$i.";";
		  	}
		}

		?>

	function initmap() {
		// set up the map
		map = new L.Map('mapid');
		// create the tile layer with correct attribution
		var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
		var osm = new L.TileLayer(osmUrl, {minZoom: 15, maxZoom: 19, attribution: osmAttrib});
		var coord;
		var coord1;
		var coordin;
		var coordfin;
		// start the map in South-East England
		map.setView(new L.LatLng(45.43455, 11.87281), 15);
		map.addLayer(osm);

		var greenMarker = L.icon({
		    iconUrl: './leaflet/images/marker-icon-green.png',
		    iconSize:     [25, 41], // size of the icon
		    iconAnchor:   [12, 2], // point of the icon which will correspond to marker's location
		    popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
		});
		var redMarker = L.icon({
		    iconUrl: './leaflet/images/marker-icon-red.png',
		    iconSize:     [25, 41], // size of the icon
		    iconAnchor:   [12, 2], // point of the icon which will correspond to marker's location
		    popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
		});
		var pMarker = L.icon({
		    iconUrl: './leaflet/images/marker-p.png',
		    iconSize:     [15, 15], // size of the icon
		    iconAnchor:   [12, 2], // point of the icon which will correspond to marker's location
		    popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
		});
		var ruMarker = L.icon({
		    iconUrl: './leaflet/images/marker-ru.png',
		    iconSize:     [15, 15], // size of the icon
		    iconAnchor:   [12, 2], // point of the icon which will correspond to marker's location
		    popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
		});

		// Aggiunta marker segnalazioni
		<?php

			if (isset($_POST['year'])) {
				$data_in = $_POST['year']."-01-01";
				$data_fin = $_POST['year']."-12-31";
			    if ($_POST['type'] == 'Tutte') {
		      		$query = pg_query("SELECT id, st_astext(geometry) AS geom, risolto FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND risolto = True ORDER BY data;");
		    	}
		    	else {
				    $query = pg_query("SELECT id, st_astext(geometry) AS geom, risolto FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = True ORDER BY data;");
		    	}
				for ($i = 0; $i < pg_num_rows($query); $i++) {
					$output = pg_fetch_array($query, $i);
					$geometry = explode("(", $output['geom']);
					$tmp = explode(" ", $geometry[1]);
					$lat = $tmp[0];
					$tmp1 = $tmp[1];
					$lng = substr($tmp1, 0, strlen($tmp1) - 1);
					echo "coord = ol.proj.transform([".$lat.", ".$lng."], \"EPSG:3003\", \"EPSG:4326\");\n\t";
					echo "ris".$i." = L.marker([coord[1], coord[0]], {icon: greenMarker}).addTo(map);\n\t"; //coord).addTo(map);"; //", {icon: ruMarker}).addTo(map);\n\t";
					echo "ris".$i.".bindPopup(\"<b>Segnalazione ".$output['id']."</b>\");\n\t";
				}

			    if ($_POST['type'] == 'Tutte') {
			        $query = pg_query("SELECT id, st_astext(geometry) AS geom, risolto FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND risolto = False ORDER BY data;");
			    }
			    else {
			      	$query = pg_query("SELECT id, st_astext(geometry) AS geom, risolto FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = False ORDER BY data;");
			    }
				for ($i = 0; $i < pg_num_rows($query); $i++) {
					$output = pg_fetch_array($query, $i);
					$geometry = explode("(", $output['geom']);
					$tmp = explode(" ", $geometry[1]);
					$lat = $tmp[0];
					$tmp1 = $tmp[1];
					$lng = substr($tmp1, 0, strlen($tmp1) - 1);
					echo "coord = ol.proj.transform([".$lat.", ".$lng."], \"EPSG:3003\", \"EPSG:4326\");\n\t";
					echo "noris".$i." = L.marker([coord[1], coord[0]], {icon: redMarker}).addTo(map);\n\t"; //coord).addTo(map);"; //", {icon: ruMarker}).addTo(map);\n\t";
					echo "noris".$i.".bindPopup(\"<b>Segnalazione ".$output['id']."</b>\");\n\t";
				}
			}

		// Inserimento rete idrica
		// Punti RU

			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM ru;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				$output = pg_fetch_array($query, $i);
				$geometry = explode("(", $output['geom']);
				$tmp = explode(" ", $geometry[1]);
				$lat = $tmp[0];
				$tmp1 = $tmp[1];
				$lng = substr($tmp1, 0, strlen($tmp1) - 1);
				echo "coord = ol.proj.transform([".$lat.", ".$lng."], \"EPSG:3003\", \"EPSG:4326\");\n\t";
				echo "markerru".$i." = L.marker([coord[1], coord[0]], {icon: ruMarker, opacity: 0.9}).addTo(map);\n\t"; //coord).addTo(map);"; //", {icon: ruMarker}).addTo(map);\n\t";
				echo "markerru".$i.".bindPopup(\"<b>RU ".$output['id']."</b>\");\n\t";
			    echo "markerru".$i.".setOpacity(0);\n\t";
			    echo "markerru".$i.".unbindPopup();\n\t";
			    echo "map.closePopup();\n\t";
			}

			// Pozzetti
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM pozzetto;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				$output = pg_fetch_array($query, $i);

				$geometry = explode("(", $output['geom']);

				$tmp = explode(" ", $geometry[1]);
				$lat = $tmp[0];
				$tmp1 = $tmp[1];
				$lng = substr($tmp1, 0, strlen($tmp1) - 1);

				echo "coord = ol.proj.transform([".$lat.", ".$lng."], \"EPSG:3003\", \"EPSG:4326\");\n\t";
				echo "markerpoz".$i." = L.marker([coord[1], coord[0]], {icon: pMarker, opacity: 0.9}).addTo(map);\n\t"; //coord).addTo(map);"; //", {icon: ruMarker}).addTo(map);\n\t";
				echo "markerpoz".$i.".bindPopup(\"<b>Pozzetto ".$output['id']."</b>\");\n\t";
			    echo "markerpoz".$i.".setOpacity(0);\n\t";
			    echo "markerpoz".$i.".unbindPopup();\n\t";
			    echo "map.closePopup();\n\t";
			}

			// Tratte
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM tratta WHERE tipologia = True;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				$output = pg_fetch_array($query, $i);
				$geometry = explode("(", $output['geom']);
				$tmp = explode(",", $geometry[1]);
				$tmp[1] = substr($tmp[sizeof($tmp) - 1], 0, strlen($tmp[sizeof($tmp) - 1]) - 1);
				// $tmp [0] primo punto
				// $tmp[1] secondo punto
				$p1 = explode(" ", $tmp[0]);
				$lat1 = $p1[0];
				$lng1 = $p1[1];

				$p2 = explode(" ", $tmp[1]);
				$lat2 = $p2[0];
				$lng2 = $p2[1];

				echo "coordin = ol.proj.transform([".$lat1.", ".$lng1."], \"EPSG:3003\", \"EPSG:4326\");\n\t";
				echo "coordfin = ol.proj.transform([".$lat2.", ".$lng2."], \"EPSG:3003\", \"EPSG:4326\");\n\t";
				echo "coord1 = [[coordin[1], coordin[0]], [coordfin[1], coordfin[0]]];\n\t";
				echo "polyline".$i." = L.polyline(coord1, {color: 'blue', weigth: 1, opacity: 0.5},).addTo(map);\n\t";
				echo "polyline".$i.".bindPopup(\"<b>Tratta ".$output['id']."</b>\");\n\t";
			    echo "polyline".$i.".setStyle({opacity: 0});\n\t";
			    echo "polyline".$i.".unbindPopup();\n\t";
			    echo "map.closePopup();\n\t";
			}
		?>
		
		}

		initmap();
		var marker = null; //L.marker([45.5, 11.09]).addTo(map);
		var flag = 0;

		map.on('click', onMapClick);

		function check() {
			// Controllo la presenza di un marker
			if (flag == 1) {
				// Invio alla pagina di inserimento
				document.getElementById('go').action = "./checksig.php";
				document.getElementById('go').submit();
			}
			else {
				// Messaggio per selezione coordinate sulla mappa
				alert("Punto sulla mappa non selezionato");
			}
		}

		function t() {
			if (document.getElementById('t').value == 1) {
				document.getElementById('t').value = 0;
				
		<?php
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM tratta WHERE tipologia = True;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				echo "polyline".$i.".setStyle({opacity: 0});\n\t";
		      	echo "polyline".$i.".unbindPopup();\n\t";
		      	echo "map.closePopup();\n\t";
			}
		?>

			}
			else {
				document.getElementById('t').value = 1;
		<?php
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM tratta WHERE tipologia = True;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				echo "polyline".$i.".setStyle({opacity: 0.5});\n\t";
	      		echo "polyline".$i.".bindPopup(\"<b>Tratta ".$output['id']."</b>\");\n\t";
			}
		?>

			}
		}

		function poz() {
			if (document.getElementById('poz').value == 1) {
				document.getElementById('poz').value = 0;
				
		<?php
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM pozzetto;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				echo "markerpoz".$i.".setOpacity(0);\n\t";
			    echo "markerpoz".$i.".unbindPopup();\n\t";
			    echo "map.closePopup();\n\t";
			}		
		?>
			
			}
			else {
				document.getElementById('poz').value = 1;
		
		<?php
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM pozzetto;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				echo "markerpoz".$i.".setOpacity(0.9);\n\t";
		      	echo "markerpoz".$i.".bindPopup(\"<b>Pozzetto ".$output['id']."</b>\");\n\t";
			}
		?>

			}
		}

		function ru() {
			if (document.getElementById('ru').value == 1) {
				document.getElementById('ru').value = 0;
				
		<?php
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM ru;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				echo "markerru".$i.".setOpacity(0);\n\t";
		      	echo "markerru".$i.".unbindPopup();\n\t";
		      	echo "map.closePopup();\n\t";
			}
			
		?>
			}
			else {
				document.getElementById('ru').value = 1;
		
		<?php
			$query = pg_query("SELECT id, st_astext(geometry) AS geom FROM ru;");
			for ($i = 0; $i < pg_num_rows($query); $i++) {
				echo "markerru".$i.".setOpacity(0.9);\n\t";
		      	echo "markerru".$i.".bindPopup(\"<b>RU ".$output['id']."</b>\");\n\t";
			}
			
		?>
			}
		}

		function ris() {
			if (document.getElementById('ris').value == 1) {
				document.getElementById('ris').value = 0;
				
		<?php
		    if (isset($_POST['year'])) {
		      	// Devo riempire la tabella
		      	$data_in = $_POST['year']."-01-01";
		      	$data_fin = $_POST['year']."-12-31";
		      	if ($_POST['type'] == 'Tutte') {
		  		    $query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND risolto = True ORDER BY data;");
		      	}
		      	else {
		        	$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = True ORDER BY data;");
		      	}
		  		for ($i = 0; $i < pg_num_rows($query); $i++) {
		  			echo "ris".$i.".setOpacity(0);\n\t";
		  		}
		    }
		  	
		?>
			}
			else {
				document.getElementById('ris').value = 1;
				
		<?php
		    if (isset($_POST['year'])) {
		      	// Devo riempire la tabella
		      	$data_in = $_POST['year']."-01-01";
		      	$data_fin = $_POST['year']."-12-31";
		      	if ($_POST['type'] == 'Tutte') {
		  		    $query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND risolto = True ORDER BY data;");
		      	}
		      	else {
			        $query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = True ORDER BY data;");
			    }
		  		for ($i = 0; $i < pg_num_rows($query); $i++) {
		  		    echo "ris".$i.".setOpacity(0.9);";
		  		}
		    }
		
		?>
			}
		}

		function noris() {
			if (document.getElementById('noris').value == 1) {
				document.getElementById('noris').value = 0;
				
		<?php
		    if (isset($_POST['year'])) {
		      	// Devo riempire la tabella
		      	$data_in = $_POST['year']."-01-01";
		      	$data_fin = $_POST['year']."-12-31";
		      	if ($_POST['type'] == 'Tutte') {
		  		    $query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND risolto = False ORDER BY data;");
		      	}
		      	else {
		        	$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = False ORDER BY data;");
		      	}
		  		for ($i = 0; $i < pg_num_rows($query); $i++) {
		  			echo "noris".$i.".setOpacity(0);\n\t";
		  		}
		    }
				
		?>
			}
			else {
				document.getElementById('noris').value = 1;
		
		<?php
		    if (isset($_POST['year'])) {
		      	// Devo riempire la tabella
		      	$data_in = $_POST['year']."-01-01";
		      	$data_fin = $_POST['year']."-12-31";
		      	if ($_POST['type'] == 'Tutte') {
		  		    $query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND risolto = False ORDER BY data;");
		      	}
		      	else {
		        	$query = pg_query("SELECT * FROM segnalazione WHERE data >= '".$data_in."' AND data <= '".$data_fin."' AND tipologia = '".$_POST['type']."' AND risolto = False ORDER BY data;");
		      	}
		  		for ($i = 0; $i < pg_num_rows($query); $i++) {
		  			echo "noris".$i.".setOpacity(0.9);";
		  		}
		    }
		
		?>
			}
		}
	</script>

<?php
} 
?>

</html>
