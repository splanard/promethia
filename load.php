<?php
array_shift($argv); // don't need the name of the file...
$nargs = count($argv);
if( $nargs != 1 || $argv[0] == "help" ){
	echo "Load the neural network configuration from a file.".PHP_EOL
			."Usage: php load.php filepath".PHP_EOL.PHP_EOL
			."   filepath\tThe path of the file containing the network configuration to load.";
	exit($nargs == 1 && $argv[0] == "help" ? 0 : 1);
}

// Check the file
$filepath = $argv[0];
if( !is_file( $filepath ) ){
	echo "Error: file not found [$filepath]";
	exit(1);
}

// Copy it
if (!is_dir('.work/')) {
	mkdir('.work/');
}
copy($filepath, '.work/network');