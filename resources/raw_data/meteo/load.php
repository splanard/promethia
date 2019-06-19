<?php
$tablename = 'meteo_france_synop';
$meteo_station = '07650'; # Marignane

// DB connexion
global $db;
$db = new PDO('mysql:host=localhost;port=3306;dbname=promethia', 'root', '');

// Truncate table
println("Truncate table...");
execute("TRUNCATE $tablename");

// Import all the CSV files
$files = glob('*.csv.gz');
foreach($files as $file){
	if( preg_match("/^synop\.201[0-8]+[0-9]{2}.csv.gz$/", $file) ){
		// unzip the file
		println("Extract $file...");
		$file = ungz($file);
		// recover full filepath
		$path = str_replace("\\", "/", realpath($file));
		// load file content in DB
		println("Load $file...");
		execute("LOAD DATA INFILE '$path' "
				. "INTO TABLE $tablename "
				. "FIELDS ENCLOSED BY '' TERMINATED BY ';' "
				. "LINES TERMINATED BY '\n' "
				. "IGNORE 1 LINES");
		// remove extracted file
		println("Remove $file...");
		unlink($file);
	}
}

// Remove unused lines
println("Remove unused lines...");
execute("DELETE FROM $tablename WHERE numer_sta != $meteo_station");

function execute( $query ){
	global $db;
	if( $db->query( $query ) === false ){
		println(var_export($db->errorInfo(), true));
	}
}

function println( $message ){
	echo $message.PHP_EOL;
}

function ungz( $file ){
	// open the gzip file
	$gz = gzopen($file, 'rb');
	if( !$gz ){
		throw new \UnexpectedValueException('Could not open gzip file');
	}
	// open the dest file
	$destfn = str_replace(".gz", "", $file);
	$dest = fopen($destfn, 'wb');
	if( !$dest ){
		gzclose($gz);
		throw new \UnexpectedValueException('Could not open destination file');
	}
	// transfer data
	while( !gzeof($gz) ){
		// Read buffer-size bytes
		// Both fwrite and gzread and binary-safe
		fwrite($dest, gzread($gz, 4*1024));
	}
	// close both files
	gzclose($gz);
	fclose($dest);
	// return the dest file name
	return $destfn;
}