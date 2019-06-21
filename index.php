<?php
require_once 'src/utils.php';
require_once 'src/FullyConnectedNeuralNetwork.php';

// Read train and test data samples
function read_dataset( $path ){
	$set = [
		'data' => [],
		'y_trues' => []
	];
	if( ($handle = fopen($path, "r")) !== FALSE ){
		while( ($data = fgetcsv($handle, 200, ",")) !== FALSE ){
			array_shift($data); // we don't need the first column (date)
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
$network = FullyConnectedNeuralNetwork::create(19, [19], 1);
//$network = FullyConnectedNeuralNetwork::fromConf(file_get_contents("resources/conf/conf[2].txt"));

// Train the NN
//$network->train($train['data'], $train['y_trues'], 0.5, 1000);
$network->autoTrain($train['data'], $train['y_trues'], '10 minute');
//$conf = $network->exportConf();
//file_put_contents("export.txt", $conf);

// Test the NN
//$biggest_fire_2018 = [0.72727,0.73333,1.00000,0.65108,0.87230,0.62230,0.82716,0.01994,0.23647,0.03419,0.19373,0.08262,0.31624,0.36,0.69,0.00000,-0.00047,-0.00064,0.37627];
//echo $network->feedforward($biggest_fire_2018)[0].PHP_EOL;

//echo "loss on the test dataset: ".$network->test($test['data'], $test['y_trues']).PHP_EOL;