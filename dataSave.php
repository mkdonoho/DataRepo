<?php
	
	//variables
	$starttime = $_GET['time']; 
	$starttime = strtotime($starttime);
	$type= $_GET['type'];
	
	echo ("DATE:");

	echo date( "Y-m-d H:i:s" , $starttime);
	echo "\n";
	

	if (!isset($_GET['addr'])) die('No address set');
	$addr = $_GET['addr'];
	
	$cycle = (int)$_GET['cycle'];
	$addLine ="";

	//csv stuff
	if(file_exists("data".$addr.".csv"))
		$file = fopen(("data".$addr.".csv"), "a") or die ("Unable to open file!");
	
	else
	{
		$file = fopen(("data".$addr.".csv"), "a") or die ("Unable to open file!");
		if($type==1)
			$addLine = "Time,Data \n";
		else if($type==2)
			$addLine = "Time,Co2,TRH Temp, RH, BMP, BMP Temp, Altitude, Thermistor Temp \n";
		else if($type==3)
			$addLine = "Time,TRH Temp, RH, Soil Temperature, Soil Humidity \n";
		else
			$addLine = "Time,Data \n";
							
		fwrite($file , $addLine);
	}//end else

	$prefix = "d";
	$i=0;
	$get = $prefix . $i;
	$addLine = date( "Y-m-d H:i:s" , $starttime) ;
	while (isset($_GET[$get])) 
	{	$data = $_GET[$get];
		$addLine = $addLine . ",". $data;	
		
		$i = $i+1;
		$get = $prefix . $i;
		
	}//end while
	
	$addLine = $addLine . "\n";
	fwrite($file , $addLine);
	echo "ADDED: ";
	echo ($addLine);
	echo "\n";	
			
	fclose($file);
	
?>
