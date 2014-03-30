
<link rel="stylesheet" type="text/css" href="style.css">
<html>
	<body>
		<div id="title_div"> TEMPE </div>
		<form method='POST' action='index.php' id="query_form">
			<label for='query_area'>Insert your query here</label>
			<textarea id='query_area' name='query_area'>SELECT * FROM PEGAWAI</textarea>
			<br/>
			<label for='temporal_area' name='temporal_area'>Insert the temporal arguments</label>
			<textarea id='temporal_area' name='temporal_area' >{"operator":"", "ts":, "te":}</textarea>
			<br>
			<input type='submit' id='submit_button'/>
			Operator list : <br> 
			=, !=, contains, contained_by, before, after, overleft, overright, overlap, meets, met_by, starts, started_by, finishes, finished_by <br>
			<br/><br><br/><br>
			
		</form>
		<br/>
		<div class='result_div'>


		<?php
		$key_array = array();
		$value_array = array();

		if (isset($_POST['query_area']) && strlen($_POST['query_area'])) {
			$query = $_POST['query_area']; 

			$conn = mysqli_connect("localhost","tempe","tempe","temporaldb");
			$res = $conn->query($query);
			$row = mysqli_fetch_array($res);
			
			$keys = array_keys($row);			
			foreach($keys as $key){
				if(strlen($key)>1){
					array_push($key_array,$key);
				}
			}

			do {
				array_push($value_array, $row);
			} while($row = mysqli_fetch_array($res));			
		}

		if (isset($_POST['temporal_area'])) {
			$argument = $_POST['temporal_area'];
			$result = json_decode($argument);
			$ts = $result->ts;
			$te = $result->te;
			echo "<br>";

			$arrayIndex = count($value_array);

			if ($result->operator == "=") {
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (($value["ts"] != $ts) || ($value["te"] != $te)) {
						unset($value_array[$i]);
					} 
				}				
			}			

			if ($result->operator == "!=") {
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (($value["ts"] == $ts) && ($value["te"] == $te)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "contains") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (($value["ts"] >= $ts) && ($value["te"] >= $te)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "contained_by") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["ts"] <= $ts) && ($value["te"] <= $te))) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "before") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!($value["te"] < $ts)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "after") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!($value["ts"] > $te)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "overleft") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["te"] > $ts) && ($value["te"] < $te) && ($value["ts"] < $ts))) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "overright") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["ts"] < $te) && ($value["ts"] > $ts) && ($value["te"] > $te)	)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "overlap") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if ( (!(($value["te"] > $ts) && ($value["te"] < $te) && ($value["ts"] < $ts))) && (!(($value["ts"] < $te) && ($value["ts"] > $ts) && ($value["te"] > $te))) ){
						unset($value_array[$i]);
					}   
				}				
			}

			if ($result->operator == "meets") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!($value["te"] == $ts)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "met_by") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!($value["ts"] == $te)) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "starts") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["ts"] == $ts) && ($value["te"] < $te))) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "started_by") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["ts"] == $ts) && ($value["te"] > $te))) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "finishes") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["te"] == $te) && ($value["ts"] > $ts))) {
						unset($value_array[$i]);
					} 
				}				
			}

			if ($result->operator == "finished_by") { 
				for ($i=0;$i<$arrayIndex;$i++) {
					$value = $value_array[$i];
					if (!(($value["te"] == $te) && ($value["ts"] < $ts))) {
						unset($value_array[$i]);
					} 
				}				
			}


			print "<table border='1' id='result_table'>";
			print "<thead>";
			for($i=0;$i<count($key_array);$i++){
				print "<td><b>";
				print $key_array[$i];
				print "</b></td>";
			}			
			print "</thead>";
			foreach ($value_array as $value) {				
				print "<tr><td>".$value["nama"]."</td>";
				print "<td>".$value["gaji"]."</td>";
				print "<td>".$value["ts"]."</td>";
				print "<td>".$value["te"]."</td></tr>";
			}
			print "</table>";
		}
		?>	
		</div>
	</body>
</html>