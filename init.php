<?php
array_shift($argv); // don't need the name of the file...
$nargs = count($argv);
if( $nargs < 1 || ( $nargs = 1 && $argv[0] == "help" ) ){
	echo "Initiate a neural network.".PHP_EOL
			."Usage: php init.php nb_in [nb_hidden...]".PHP_EOL.PHP_EOL
			."   nb_in\tThe number of inputs, or the size of the input vector".PHP_EOL
			//."   nb_out\tThe number of neuron in the ouput layer".PHP_EOL
			."   nb_hidden\tFor any number of hidden layers, the number of neurons it should contain".PHP_EOL
			."   For now, the network always has 1 output neuron.".PHP_EOL.PHP_EOL
			."Ex: 'php init.php 4 4' will initiate a network with 4 inputs and 1 hidden layer with 4 neurons.";
	exit($nargs == 2 ? 0 : 1);
}

$nb_in = array_shift($argv);
//$nb_out = array_shift($argv);
$nb_out = 1;
$nb_hidden = $argv;

require_once 'src/utils.php';
require_once 'src/FullyConnectedNeuralNetwork.php';

// Create the network
$network = FullyConnectedNeuralNetwork::create($nb_in, $nb_hidden, $nb_out);

// Export it
if (!is_dir('.work/')) {
	mkdir('.work/');
}
file_put_contents(".work/network", $network->exportConf());