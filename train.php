<?php
array_shift($argv); // don't need the name of the file...
$nargs = count($argv);
if( $nargs < 3 
		|| !in_array($argv[0], ['auto', 'simple']) 
		|| ( $nargs = 1 && $argv[0] == "help" ) ){
	echo "Train the neural network, using the resources/train.csv dataset.".PHP_EOL
			."Usage: php train.php mode learn_rate duration".PHP_EOL.PHP_EOL
			."   mode\t\tTraining mode.".PHP_EOL
			."   \t\t'simple' for fixed learn rate during a number of epochs,".PHP_EOL
			."   \t\t'auto' for auto evolving learn rate during an amount of time.".PHP_EOL
			."   learn_rate\tThe learn rate (initial value in the case of 'auto' training)".PHP_EOL
			."   duration\tIn simple training, the number of epochs. In 'auto' training, the time duration of the training (strtotime format).".PHP_EOL.PHP_EOL
			."Ex: php train.php simple 0.5 2000".PHP_EOL
			."   will train the network with a learn rate of 0.5 during 2000 epochs.".PHP_EOL
			."Ex: php train.php auto 0.5 '1 hour'".PHP_EOL
			."   will train the network during 1 hour with an initial learn rate of 0.5.";
	exit($nargs == 2 ? 0 : 1);
}

require_once 'src/utils.php';
require_once 'src/FullyConnectedNeuralNetwork.php';

// Load the network
if( !is_file( '.work/network' ) ){
	echo "Error: there is no network to train"; 
	exit(1);
}
$network = FullyConnectedNeuralNetwork::fromConf( file_get_contents(".work/network") );

// Load train dataset
$train = read_dataset(__DIR__.'/resources/train.csv', 1);

// Train the network
if( $argv[0] == 'simple' ){
	$network->train($train['data'], $train['y_trues'], $argv[1], $argv[2]);
} else {
	$network->autoTrain($train['data'], $train['y_trues'], $argv[1], $argv[2]);
}

// Save the trained network
file_put_contents(".work/network", $network->exportConf());