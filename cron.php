<?php
	include_once('/var/www/includes/thevars_one.php'); 
	define('MY_DB','upeer_sat');
	$db_link = mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MY_DB, $db_link) or die("Could not select database");


	
				$sel = "SELECT * FROM  `upeer_sat`.`category`";
				$res = mysql_query($sel);
				while ($row = mysql_fetch_assoc($res)) {	
					$arrayCategory[$row['category_name']] = $row;
				
				}
	
			$satellite_api = 'https://www.n2yo.com/rest/v1/satellite/above/41.702/-76.014/0/360/0/&apiKey=G5SS8B-WJK25E-PU9GFC-3U40';
			$json =  file_get_contents($satellite_api);
			$array = json_decode($json, true);
			
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			
			foreach($array['above'] as $sattelite) {
				mysql_query("DELETE FROM `upeer_sat`.`satellite` WHERE `satellite`.`sattelite_id` = ".$sattelite['satid']."");
				mysql_query("INSERT INTO `upeer_sat`.`satellite` (`id`, `launchDate`, `intDesignator`, `latitude`, `longitude`, `satalt`, `satellite_name`, `sattelite_category`, `sattelite_id`, `satellite_category_id`) VALUES (NULL, '".$sattelite['launchDate']."', '".$sattelite['intDesignator']."', '".$sattelite['satlat']."', '".$sattelite['satlng']."', '".$sattelite['satalt']."', '".$sattelite['satname']."', '".$array['category']."', '".$sattelite['satid']."', '".$arrayCategory[$array['category']]['category_id']."');");
			}
			
?> 