<?php
function debug( $var ){
	echo var_export( $var, true ).PHP_EOL;
}

/**
 * average value of the given array
 */
function mean( array $a ){
	return array_sum($a) / count($a);
}

/**
 * dot product between two arrays
 */
function dotp( array $a, array $b ){
	// TODO: throw an error if count($a) != count($b)
	return array_sum( array_map( function($x, $y){ return $x * $y; }, $a, $b ) );
}

/**
 * Calculates the mean square error (MSE) loss
 */
function mse_loss( array $y_trues, array $y_preds ){
	if( count($y_trues) != count($y_preds) ){
		exit("Invalid arrys passed to mse_loss()");
	}
	return mean(array_map(function($a, $b){ return pow($a - $b, 2); }, $y_trues, $y_preds ));
}

/**
 * Calculates the derivative of the MSE loss of one couple y_true/y_pred.
 */
function mse_loss_deriv_one( $y_true, $y_pred ){
	return -2 * ($y_true - $y_pred);
}

function mse_loss_alt( array $y_trues, array $y_preds ){
	return mean(array_map(function($yt, $yp){ return (1 + 3*$yt) * pow($yt - $yp, 2); }, $y_trues, $y_preds ));
}
function mse_loss_alt_deriv_one( $y_true, $y_pred ){
	return (1 + 3*$y_true) * -2 * ($y_true - $y_pred);
}

function normalize_minmax( array $input, $newmin = 0, $newmax = 1 ){
	$i_min = min( $input );
	$i_max = max( $input );
	foreach($input as $i){
		$output[] = ($i-$i_min)/($i_max-$i_min)*($newmax-$newmin)+$newmin;
	}
	return $output;
}

/**
 * sigmoid activation function : f(x) = 1 / (1 + e^(-x))
 */
function sigmoid( $x ){
	return 1 / (1 + exp(-$x));
}

/**
 * derivative of sigmoid: f'(x) = f(x) * (1 - f(x))
 */
function deriv_sigmoid( $x ){
	$fx = sigmoid($x);
	return $fx * (1 - $fx);
}

/**
 * Initialize weights using Xavier's initialization.
 * @see https://hackernoon.com/how-to-initialize-weights-in-a-neural-net-so-it-performs-well-3e9302d4490f
 * 
 * @param int $nw Number of weights to initialize
 * @param int $ni Number of inputs for the layer
 * @param int $no Number of outputs for the layer
 * $param callback $rand_function the random generation function to use
 */
function xavier_init( $nw, $ni, $no, $rand_function = 'nrand' ){
	for( $i=0, $maxi=$nw; $i<$maxi; $i++ ){
		$weights[] = $rand_function(0,1) * sqrt(1/($ni+$no));
	}
	return $weights;
}

/**
 * Simulate a random number which probability of occurence follow a standard 
 * normal distribution of given mean and variance.
 * 
 * @param number $mean The mean or expectation
 * @param number $sd The standard deviation or variance
 * @return A random number following the normal distribution
 */
function nrand($mean, $sd){
    $x = mt_rand()/mt_getrandmax();
    $y = mt_rand()/mt_getrandmax();
    return sqrt(-2*log($x))*cos(2*pi()*$y)*$sd + $mean;
}

/**
 * Read a CSV dataset.
 * 
 * @param string $path The path of the dataset to read
 * @param integer $shift_cols The number of columns to ignore at the beginning of each line
 * @param integer $pop_cols The number of columns to ignore at the end of each line
 * @return array CSV dataset converted in array. The last non-ignored element of 
 * each line is considered as the y_true value. the rest of the line is the training data.
 */
function read_dataset( $path, $shift_cols = 0, $pop_cols = 0 ){
	$set = [
		'data' => [],
		'y_trues' => []
	];
	if( ($handle = fopen($path, "r")) !== FALSE ){
		while( ($data = fgetcsv($handle, 200, ",")) !== FALSE ){
			$shift = $shift_cols;
			while( $shift-- > 0){
				array_shift($data);
			}
			$pop = $pop_cols;
			while( $pop-- > 0 ){
				array_pop($data);
			}
			$set['y_trues'][] = array_pop( $data );
			$set['data'][] = $data;
		}
		fclose($handle);
	}
	return $set;
}