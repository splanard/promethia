<?php
require_once 'utils.php';

class Neuron {
	private $weights;
	private $bias;
	
	private $dout_dwi;
	private $dout_db;
	
	function __construct( array $weights, $bias ){
		$this->weights = $weights;
		$this->bias = $bias;
	}
	
	public function feedforward( array $inputs ){
		return sigmoid( $this->sum($inputs) );
	}
	
	public function backprop( array $inputs ){
		// Application of weights and bias to the input, before activation
		$s = $this->sum( $inputs );
		// Derivative of the activation function applied to the previous sum
		$dactiv_sum = deriv_sigmoid( $s );
		
		for( $i=0, $maxi=count($this->weights); $i<$maxi; $i++ ){
			$this->dout_dwi[$i] = $inputs[$i] * $dactiv_sum;
			$dout_din[] = $this->weights[$i] * $dactiv_sum;
		}
		$this->dout_db = $dactiv_sum;

		return $dout_din;
	}
	
	public function update( $learn_rate, $dL_dout ){
		for( $i=0, $maxi=count($this->weights); $i<$maxi; $i++ ){
			$this->weights[$i] -= $learn_rate * $dL_dout * $this->dout_dwi[$i];
		}
		$this->bias -= $learn_rate * $dL_dout * $this->dout_db;
	}
	
	private function sum( array $inputs ){
		return dotp( $this->weights, $inputs ) + $this->bias;
	}
	
}
