<?php
require_once 'src/utils.php';
require_once 'src/FullyConnectedNeuralNetwork.php';

// Read train and test data samples
$train = [
	'data' => [],
	'y_trues' => []
];
if( ($handle = fopen("resources/train_sample_norm.csv", "r")) !== FALSE ){
    while( ($data = fgetcsv($handle, 160, ",")) !== FALSE ){
		$train['y_trues'][] = array_pop( $data );
		$train['data'][] = $data;
    }
    fclose($handle);
}

// Create the NN
$network = new FullyConnectedNeuralNetwork(18, [18], 1);

// Train the NN
$network->train($train['data'], $train['y_trues'], 0.5, 5000);

// Test the NN
