<?php
	$Cycles = array();
	$index = 0;
	$c = fopen("Cycles.txt","r");
	if($c){
		while(($line = fgets($c)) !== false){
			//$list = explode('_',$line);
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
	$time = $_GET['time'];
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


	$name = "data.csv";
	$m = fopen("$name", "a") or die("Couldn't find file!");
	$file = "logv2.txt";
	$f = fopen("$file", "a") or die("Couldn't find file!");

	fwrite($m,$addr . ",");
	fwrite($m,$time . ",");
	fwrite($m,$cycle . ",");
	
	$append = "";
	$prefix = "d";
	$i=0;
	$get = $prefix . $i;
	while (isset($_GET[$get])) {
		$append .= "<br><br><center><table border='1'><tr><td colspan='12' bgcolor='#$r$g$b'><strong>ADDRESS " . $_GET['addr'] . "." . $cycle . " @" . $time;
		$data = explode('_',$_GET[$get]);

		$n = count($data);
		echo $data[0];
		switch($data[0]){
			case "tc":
				$append .= "   Thermocouple";
				fwrite($m,"Thermocouple,");
				break;
			case "tt":
				$append .= "   TRH Temperature";
				fwrite($m,"TRH Temperature,");
				break;
			case "th":
				$append .= "   TRH Humidity";
				fwrite($m,"TRH Humidity,");
				break;
			case "sm":
				$append .= "   Soil Moisture";
				fwrite($m,"Soil Moisture,");
				break;
			case "c2":
				$append .= "   CO2";
				fwrite($m,"CO2,");
				break;
			case "o2":
				$append .= "   O2";
				fwrite($m,"O2,");
				break;
			default:
				$append .= "   Unknown Sensor";
				fwrite($m,"Unknown Sensor,");
				break;
		}
		$append .= "</strong></td></tr>";
		for ($j=1; $j<$n; $j=$j+1) {
			$append .= "<tr align = center><td>" . $j . "</td><td>" . $data[$j] . "</td></tr>";
			fwrite($m,$data[$j] . ",");
		}
		$append .= "</table></center>";
		fwrite($f, $append);
		fwrite($m,"\n");
		$i = $i+1;
		$get = $prefix . $i;
		$append = "";
	}
	fclose($f);
	fclose($m);
	
	if($Flag){
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
