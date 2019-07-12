<?php
array_shift($argv); // don't need the name of the file...
$nargs = count($argv);
if( $nargs > 1 || ($nargs == 1 && $argv[0] == "help") ){
	echo "Test the neural network, using the resources/test.csv dataset.".PHP_EOL
			."Usage: php test.php [--ignore_zeros]".PHP_EOL.PHP_EOL
			."   ignore_zeros\tDo not display test samples where the target result is 0.";
	exit( $nargs == 1 && $argv[0] == "help" ? 0 : 1);
}
$ignore_zeros = ($nargs >= 1 && $argv[0] == "--ignore_zeros");

require_once 'src/utils.php';
require_once 'src/FullyConnectedNeuralNetwork.php';

// Load the network
if( !is_file( '.work/network' ) ){
	echo "Error: there is no network to test"; 
	exit(1);
}
$network = FullyConnectedNeuralNetwork::fromConf( file_get_contents(".work/network") );

// Load test dataset
$test = read_dataset(__DIR__.'/resources/test.csv');
$n = 0;
$false_neg_sum = 0;
for($i=0; $i<count($test['data']); $i++){
	$s = $test['data'][$i];
	$y = $test['y_trues'][$i];
	$date = array_shift($s);
	$surface = array_shift($s);
	if( $y == 1 || !$ignore_zeros ){
		$y_pred = $network->feedforward( $s );
		echo "$date: ($surface m²) -> ".number_format($y_pred*100, 1)."%".PHP_EOL;
		if( $y == 1 ){
			$n++;
			$false_neg_sum += (1-$y_pred);
		}
	}
}
echo "False negative rate: ".number_format($false_neg_sum/$n, 4).PHP_EOL;