<?php
require_once 'utils.php';
require_once 'Neuron.php';

class FullyConnectedLayer {
	
	/** 
	 * The neurons of the layer 
	 * @var Neuron[] 
	 */
	private $neurons = array();
	
	function __construct( $nb_in, $nb_neurons, $nb_out ){
		for( $i=0, $maxi=$nb_neurons; $i<$maxi; $i++ ){
			$weights = xavier_init($nb_in, $nb_in, $nb_out);
			//$bias = stats_rand_gen_normal(0, 1);
			//Use of mt_rand() because stats_rand_gen_normal() always returns the same values...
			$bias = mt_rand(-1.5, 1.5);
			$this->neurons[] = new Neuron( $weights, $bias );
		}
	}
	
	function feedforward( array $inputs ){
		foreach($this->neurons as $n){
			$outputs[] = $n->feedforward( $inputs );
		}
		return $outputs;
	}
	
	function backprop( array $inputs ){
		foreach($this->neurons as $n){
			$dout_din = $n->backprop( $inputs );
			/* WARNING! I didn't know what to do here!
			 * To update a layer's neurons, we need to calculate dL/dwi and dL/db
			 * But, if the next layer has multiple neurons, dL/dwi can be splitted in different ways.
			 * For example, if the next layer is the ouput layer and has 2 neurons, which outputs are o1 and o2.
			 * dL/dwi = dL/do1 * do1/dwi
			 * dL/dwi = dL/do2 * do2/dwi
			 * How do I choose how to chain the partial derivatives? Which of the "derivative path" should I use?
			 * Here, I made the choice of considering always the path going through the first neuron of the next layer.
			 * It is why below I ignore the `dout_din` other than the first one...
			 */
			if( !isset( $dout_din0 ) ){
				$dout_din0 = $dout_din;
			}
		}
		return $dout_din0;
	}
	
	function update( $learn_rate, array $dL_dout ){
		for($i=0, $maxi=count($this->neurons); $i<$maxi; $i++){
			$this->neurons[$i]->update($learn_rate, $dL_dout[$i]);
		}
	}
	
}
