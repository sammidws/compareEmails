<?php

// bash command:
//   php -e compare_emails.php 'jul/KG_FULL_DB_020714.txt' 'jul/SA_FULL_DB_020714.txt'

// For testing 
// bash command:
// 		php -e compare_emails.php 'jul/a.txt' 'jul/b.txt'
// results are cross = 1,4,6
// results are KG = 2
// results are SA = 3


define("CROSSOVER", "CROSSOVER MEMBERS");
define("KG", "KG ONLY MEMBERS");
define("SA", "SA ONLY MEMBERS");


ini_set('memory_limit', '-1');
try {
	if( isset( $argv[1] )) { $input1 = $argv[1]; } else { throw new Exception( 'Email list 1 has not been set.'); }
	if( isset( $argv[2] )) { $input2 = $argv[2]; } else { throw new Exception( 'Email list 2 has not been set.'); }

	$emaillists = new compareEmailLists();
	$a = $emaillists->getFileContent($input1);
	$b = $emaillists->getFileContent($input2);
} catch (Exception $e) {
    echo 'Exception: ',  $e->getMessage(), "\n";

}
$emaillists->writeCSV( $a, $b, CROSSOVER );
$emaillists->writeCSV( $a, $b, KG );
$emaillists->writeCSV( $a, $b, SA );


CLASS compareEmailLists
{
	function getFileContent ($txtfile)
	{
		if (!file_exists($txtfile)) {
			throw new Exception($txtfile . ' does not exist.');
			return false;
		}
		$file = fopen($txtfile, "r");
		$arr = array();
		if (!feof($file)) {
			while (!feof($file)) {
			    
			    $arr[] = strtolower(trim(fgets($file)));

			}			
		}

		fclose($file);
		return array_unique($arr);

	}


	function writeCSV( $a, $b, $type)
	{

		if ( $type == CROSSOVER) {
			$arr = array( array_intersect($a, $b) ); // 
		} else if ( $type == KG) {
			$arr = array( array_diff($a, $b)) ; // 
		} else if ( $type == SA) {
			$arr = array( array_diff($b, $a)) ; // 
		}
	    
		
		//open the output file for writing
		$fp = fopen( 'results/'.$type .'.csv', 'w+');

		//write to the csv file (comma separated, double quote enclosed)
		foreach ($arr as $fields) {
			foreach ($fields as $field) {
				if( $field != '') {
					fwrite($fp, $field.",\n");
				}
				
			}
		}
		//close the file
		fclose($fp);
		unset( $arr );
	}
}