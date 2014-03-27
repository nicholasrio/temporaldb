<form method='POST' action='index.php'>
	<label for='query_area'>Insert your query here</label>
	<textarea id='query_area' name='query_area'></textarea>
	<br/>
	<label for='temporal_area' name='temporal_area'>Insert the temporal arguments</label>
	<textarea id='temporal_area' name='temporal_area'></textarea>
	<br/>
	<input type='submit'/>
</form>
<br/>
<div class='result_div'>


<?php
if(isset($_POST['query_area'])){
	$query = $_POST['query_area'];

	$conn = mysqli_connect("localhost","tempe","tempe","temporaldb");
	$res = $conn->query($query);
	$row = mysqli_fetch_array($res);
	
	$keys = array_keys($row);
	$key_array = array();
	foreach($keys as $key){
		if(strlen($key)>1){
			array_push($key_array,$key);
		}
	}
	print_r($key_array);
	print "<table border='1'>";
	print "<thead>";
	for($i=0;$i<count($key_array);$i++){
		print "<td><b>";
		print $key_array[$i];
		print "</b></td>";
	}
	print "</thead>";

	do{
		print "<tr>";
		for($i=0;$i<count($key_array);$i++){
			print "<td>";
			print $row[$key_array[$i]];
			print "</td>";
		}
		print "</tr>";		
	}while($row = mysqli_fetch_array($res));

	print "</table>";
}

?>	

</div>