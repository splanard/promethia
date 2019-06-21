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
	
	/**
	 * Feed the neuron with inputs and process it.
	 * 
	 * @param array $inputs inputs
	 * @return number output
	 */
	public function feedforward( array $inputs ){
		return sigmoid( $this->sum($inputs) );
	}
	
	/**
	 * Perform back propagation on the neuron and register partial derivatives
	 * for a later update.
	 * 
	 * @param array $inputs The neuron's inputs
	 * @return array partial derivatives d(output)/d(input)
	 */
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
	
	/**
	 * Update the neuron's weights and bias with the pre-compiled partial derivatives.
	 * 
	 * @param number $learn_rate Learning rate
	 * @param number $dL_dout partial derivative d(loss)/d(out)
	 */
	public function update( $learn_rate, $dL_dout ){
		for( $i=0, $maxi=count($this->weights); $i<$maxi; $i++ ){
			$this->weights[$i] -= $learn_rate * $dL_dout * $this->dout_dwi[$i];
		}
		$this->bias -= $learn_rate * $dL_dout * $this->dout_db;
	}
	
	/**
	 * Apply weights and bias to the inputs.
	 * 
	 * @param array $inputs inputs
	 * @return number the result
	 */
	private function sum( array $inputs ){
		return dotp( $this->weights, $inputs ) + $this->bias;
	}
	
	/**
	 * Export the neuron configuration: its weights and bias.
	 * @return array The neuron configuration
	 */
	public function exportConf(){
		return [ $this->weights, $this->bias ];
	}
}
