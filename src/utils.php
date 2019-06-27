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
	return mean(array_map(function($a, $b){ return (1 + 3*$a) * pow($a - $b, 2); }, $y_trues, $y_preds ));
}
function mse_loss_alt_deriv_one( $y_true, $y_pred ){
	return (1 + 3*$y_true) * -2 * ($y_true - $y_pred);
}
function false_neg_loss( array $y_trues, array $y_preds ){
	return mean(array_map(function($a, $b){ return pow($a - $b, 2) * $a; }, $y_trues, $y_preds ));
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
 */
function xavier_init( $nw, $ni, $no ){
	for( $i=0, $maxi=$nw; $i<$maxi; $i++ ){
		//$weights[] = stats_rand_gen_normal(0, 1) * sqrt(1/($ni+$no));
		// Use of mt_rand() because stats_rand_gen_normal() always returns the same values...
		$weights[] = mt_rand(-1.5, 1.5) * sqrt(1/($ni+$no));
	}
	return $weights;
}