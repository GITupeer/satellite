<?php
	include_once('/var/www/includes/thevars_one.php'); 
	define('MY_DB','upeer_sat');
	$db_link = mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MY_DB, $db_link) or die("Could not select database");


	
				$sel = "SELECT `latitude`, `longitude`, `satellite_name` FROM  `upeer_sat`.`satellite` LIMIT 1000";
				$res = mysql_query($sel);
				$query = '';
				$count=0; 
				while ($row = mysql_fetch_assoc($res)) {	
					if (empty($query)) {
						$query = '["'.$row['satellite_name'].'", '.$row['latitude'].', '.$row['longitude'].']';
					} else {
						$query .= ', ["'.$row['satellite_name'].'", '.$row['latitude'].', '.$row['longitude'].']';
					}
					$count++;
				}
				
				$query .= '';
	
		$arr['data'] = $query;
		$arr['status'] = 'OK';
		$arr['counter'] = $count;
		$arr['information'] = "se of this API requires the author's consent.";
		
		echo json_encode($arr, true);
			
?> 