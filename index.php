<?php 

/*
	echo "Hello World"; 
 	echo date ('Y-m-d H:i:s'); 


	echo '<br>';
	
	if (isset($_GET['data'])) {
		$data = $_GET['data'];
		echo '<br><br><strong>Return: </strong>'.$data;
	} else {
		echo '<br>No data sent. :c';
	}

	if (isset($_GET['var'])) {
		echo '<br>Also got var=' . $_GET['var'];
	} else {
		echo 'Nothing set for var';
	}
*/

?>

<?php
	// Global PHP Variables
	
	// Colors
	$okay = '#00FF00';
	$bad = '#FF0000';
	$warn = '#FFFF00';

	// Delay Warning
	$delayWarn = 10; //seconds

?>

<html>
	<head>
		<title>BAS Node Viewer</title>
		<style>
			* {
				font-family: 'Helvetica';
			}

			table {
				border-collapse: collapse;
			}

			table td {
				border: 1px solid #000;
				border-width: 2px 2px;
			}
		</style>
	</head>
	<body>
		<center>
			<h1>Big Ass Solutions Node Viewer</h1>
		</center>
		<center>Current as of <strong><?php echo time();?> </strong></center>

		<center>
			<?php
				// Connect to SQL Server
				$server = 'localhost';
				$user = 'root';
				$pass = 'ASSMAN';
				$dbname = 'bigassdata';

				$conn = mysql_connect($server, $user, $pass) or die("Can't connect to SQL server!");
				mysql_select_db($dbname);// or die("Can't connect to database!");
			?>
			<table>
			<?php
				// Loop through all possible addresses to get status' of all nodes
				for ($i=0; $i<256; $i++) {
					$sql = "SELECT * FROM `raw-data-stream` WHERE addr=" . $i  . " AND time= (SELECT MAX(time) FROM `raw-data-stream`  WHERE addr = " . $i . ")";
					$result = mysql_query($sql);
					
					echo '<tr><td>'.$i.'</td>';
					if ($row = mysql_fetch_assoc($result)) {
						$stamp = strtotime($row['time']);
						$elapsed = time()-$stamp;
						if ($elapsed >= $delayWarn) {
							echo '<td bgcolor="'.$okay.'">OKAY</td><td>'.$row['time'].'</td>';
						} else {
							echo '<td bgcolor="'.$warn.'">WARNING</td><td>'.$row['time'].'</td>';
						}
						echo '<td>'.$elapsed.' '.$stamp.'</td>';	
					} else {
						echo '<td bgcolor="'.$bad.'">FAIL</td><td>null</td>';
					}
					echo '</tr>';
				}

			?>
			</table>
		</center>
	</body>
</html>
