<?php
	$Cycles = array();
	$index = 0;
	$c = fopen("Cycles.txt","r");
	if($c){
		while(($line = fgets($c)) !== false)
		{
			array_push($Cycles,explode('_',$line));
		}
		fclose($c);
	}
	else{
		echo "error";
	}
	$Flag = 1;
	$actual = 0;
	if (!isset($_GET['addr'])) die('No address set');
	$addr = $_GET['addr'];
	for($a = 0;$a<sizeof($Cycles);$a++){
		if($Cycles[$a][0] == $addr){
			$actual = (int)$Cycles[$a][1];
			$index = $a;
		}
	}
	$cycle = (int)$_GET['cycle'];
	if($cycle == $actual){
		$Flag = 0;
	}	

	$bin = decbin(255-$addr);
	while (strlen($bin) < 8) {
		$bin = "0" . $bin;
	}
	$r = substr($bin,0,3);
	$g = substr($bin,3,3);
	$b = substr($bin,6,2);

	$r = round(bindec($r)*255/8);
	$g = round(bindec($g)*255/8);
	$b = round(bindec($b)*255/4);

	$r = dechex($r);
	$g = dechex($g);
	$b = dechex($b);

	//echo "$r $g $b ";


	$name = "data.txt";
	$m = fopen("$name", "a") or die("Couldn't find file!");
	$file = "log.txt";
	$f = fopen("$file", "a") or die("Couldn't find file!");

	fwrite($m,$addr . ",");
	fwrite($m,$cycle . ",");

	$append = "<br><br><center><table border='1'><tr><td colspan='12' bgcolor='#$r$g$b'><strong>ADDRESS " . $_GET['addr'] . "." . $cycle . "</strong></td></tr>";
	$append .= "<tr><td>Address</td><td>Time</td><td>Air Velocity</td><td>Theta Max</td><td>Phi Max</td><td>Address</td><td>Time</td><td>Air Velocity</td><td>Theta Max</td><td>Phi Max</td></tr>";

	$prefix = "d";
	$i=0;
	$get = $prefix . $i;
	while (isset($_GET[$get])) {
		if (($i)%2 == 0) {
			$append .= '<tr>';
		}
		$append .= "<td width='50' bgcolor='#99ccff'><strong>" . $i . "</strong></td>";
		$data = explode('_',$_GET[$get]);

		$n = count($data);
		for ($j=0; $j<$n; $j=$j+1) {
			$append .= "<td width='100'>" . $data[$j] . "</td>";
			fwrite($m,$data[$j] . ",");
		}
		fwrite($m,"\n");
		if ($n < 3) {
			$append .= "<td bgcolor='#777777' colspan='" . (3-$n) . "'></td>";
		}

		if (($i)%2 == 1) {
			$append .= '</tr>';
		}
		$i = $i + 1;
		$get = $prefix . $i;
	}
	if ($i%2!=0) {
		$append .= "<td bgcolor='#777777' colspan='" . ((2-($i%2))*4)  . "'></tr>";
	}

	$append .= "</table></center>";

	fwrite($f, $append);
	fclose($f);
	
	fclose($m);
	
	if($Flag){
		//$r = fopen("$addr","a");
		//fwrite($r,"Read_" . $actual . "_" . ($cycle - 1));
		echo "Read_" . $actual . "_" . ($cycle - 1);
		fclose($r);
	}
	else{
		echo "OK";
	}
	$Cycles[$index][1] = $Cycles[$index][1] + 1;
	
	$c = fopen("Cycles.txt","w");
	for($b=0;$b<sizeof($Cycles);$b++){
		fwrite($c,$Cycles[$b][0] . "_" . $Cycles[$b][1]);
	}
	if( $cycle%24 == 0 ){
		$out = "";
		$return = "";
		//passthru("/home/pi/Dropbox-Uploader/dropbox_uploader.sh upload /var/www/html/data.txt data.txt", $return);
		//$command = escapeshellcmd('/usr/custom/test.py');
		//$output = shell_exec($command);
		//echo $output;
		passthru("sudo python upload.py");
	}
	
	//echo $_GET['addr'] . " sent " . $i . " results. ";
	//if ($i > 0) {
	//	echo "First data point was ". $_GET['d0'];
	//} 

?>
