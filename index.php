<?php
require_once 'src/utils.php';
require_once 'src/FullyConnectedNeuralNetwork.php';

// Read train and test data samples
function read_dataset( $path, $shift_date = true ){
	$set = [
		'data' => [],
		'y_trues' => []
	];
	if( ($handle = fopen($path, "r")) !== FALSE ){
		while( ($data = fgetcsv($handle, 200, ",")) !== FALSE ){
			if( $shift_date ){
				array_shift($data); // we don't need the first column (date)
			}
			$set['y_trues'][] = array_pop( $data );
			$set['data'][] = $data;
		}
		fclose($handle);
	}
	return $set;
}
$train = read_dataset('resources/train_sample.csv');
//echo var_export($train['data'], true);
$test = read_dataset('resources/test_sample.csv');

// Create the NN
$network = FullyConnectedNeuralNetwork::create(19, [19, 8], 1);
//$network = FullyConnectedNeuralNetwork::fromConf(file_get_contents("resources/conf/conf[4].txt"));

// Train the NN
//$network->train($train['data'], $train['y_trues'], 0.5, 1000);
$network->autoTrain($train['data'], $train['y_trues'], '1 hour', 0.5);
//$conf = $network->exportConf();
//file_put_contents("export.txt", $conf);

// Test the NN
$biggest_fire_2018 = [0.72727,0.73333,1.00000,0.65108,0.87230,0.62230,0.82716,0.01994,0.23647,0.03419,0.19373,0.08262,0.31624,0.36,0.69,0.00000,-0.00047,-0.00064,0.37627];
echo "score on the biggest fire of 2018: ".$network->feedforward($biggest_fire_2018).PHP_EOL;

$network->test($test['data'], $test['y_trues']);
/*
for($i=0; $i<count($test['data']); $i++){
	$s = $test['data'][$i];
	$y = $test['y_trues'][$i];
	$date = array_shift($s);
	if( $y == 1 ){
		echo "$date: ($y) -> ".number_format($network->feedforward( $s )*100, 3)."%".PHP_EOL;
	}
}
*/