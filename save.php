<?php
array_shift($argv); // don't need the name of the file...
$nargs = count($argv);
if( $nargs != 1 || $argv[0] == "help" ){
	echo "Save the neural network configuration in a file.".PHP_EOL
			."Usage: php save.php filepath".PHP_EOL.PHP_EOL
			."   filepath\tThe path of the target file, including the filename.";
	exit($nargs == 1 && $argv[0] == "help" ? 0 : 1);
} 

// Check the network
if( !is_file( '.work/network' ) ){
	echo "Error: there is no network to save"; 
	exit(1);
}

// Copy the file
$filepath = $argv[0];
$dir = dirname($filepath);
if( !is_dir($dir) ){
	mkdir($dir, 0777, true);
}
copy('.work/network', $argv[0]);