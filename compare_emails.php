<?php

// bash command:
//   php -e compare_emails.php 'month/filename_for_list_1.txt' 'month/filename_for_list_2.txt'
//   example: php -e compare_emails.php 'jul/A_FULL_DB_020714.txt' 'jul/B_FULL_DB_020714.txt'

// For testing 
// bash command:
// 		php -e compare_emails.php 'jul/a.txt' 'jul/b.txt'

// a.txt = 1,2,2,  4,6
// b.txt = 1,    3,4,6,6

// results are Crossover = 1,4,6
// results are A = 2
// results are B = 3

// set memory limit to undifined
ini_set('memory_limit', '-1');

// define filename constant here
define("CROSSOVER", "CROSSOVER MEMBERS");
define("KG", "KG ONLY MEMBERS");
define("SA", "SA ONLY MEMBERS");


// try/catch 2 files as arguments and check if file exists before retreiving content
try {
	if( isset( $argv[1] )) { $input1 = $argv[1]; } else { throw new Exception( 'Email list 1 has not been set.'); }
	if( isset( $argv[2] )) { $input2 = $argv[2]; } else { throw new Exception( 'Email list 2 has not been set.'); }

	// instatiate the compareEmailList class
	$emaillists = new compareEmailLists();
	
	// get file content
	$a = $emaillists->getFileContent($input1);
	$b = $emaillists->getFileContent($input2);
} catch (Exception $e) {

	// catch exception gracefully
    echo 'Exception: ',  $e->getMessage(), "\n";

}
// write Emails that exist in both lists to CSV 
$emaillists->writeCSV( $a, $b, CROSSOVER );

// write Emails that exist in only KG list to CSV 
$emaillists->writeCSV( $a, $b, KG );

// write Emails that exist in only KG list to CSV 
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

		//write to the csv file (comma separated)
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