<?php
require_once 'utils.php';
require_once 'Neuron.php';

class FullyConnectedLayer {
	
	/** 
	 * The neurons of the layer 
	 * @var Neuron[] 
	 */
	private $neurons;
	
	function __construct(  ){
		$this->neurons = array();
	}
	
	/**
	 * Provide the layer with inputs.
	 * 
	 * @param array $inputs The layer inputs
	 * @return array The layer outputs
	 */
	function feedforward( array $inputs ){
		foreach($this->neurons as $n){
			$outputs[] = $n->feedforward( $inputs );
		}
		return $outputs;
	}
	
	/**
	 * Perform back propagation on the layer, improving the properties of each neurons.
	 * 
	 * @param array $inputs The layrer inputs.
	 * @return array The partial derivatives d(outputs)/d(inputs).
	 */
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
	
	/**
	 * Update all the neurons of the layer.
	 * 
	 * @param number $learn_rate Learning rate.
	 * @param array $dL_dout Partial derivatives d(loss)/d(neuron output)
	 */
	function update( $learn_rate, array $dL_dout ){
		for($i=0, $maxi=count($this->neurons); $i<$maxi; $i++){
			$this->neurons[$i]->update($learn_rate, $dL_dout[$i]);
		}
	}
	
	/**
	 * Export the configuration of the layer.
	 * @return array Layer configuration.
	 */
	public function exportConf(){
		foreach( $this->neurons as $n ){
			$conf[] = $n->exportConf();
		}
		return $conf;
	}
	
	/**
	 * Instanciate a fully connected layer.
	 * 
	 * @param int $nb_in The number of inputs of the layer. 
	 * It will determine the number of weights of each neuron.
	 * @param int $nb_neurons The number of neurons of the layer.
	 * @param int $nb_out The number of output, meaning the number of neurons in the next layer.
	 * @return FullyConnectedLayer The layer
	 */
	public static function create( $nb_in, $nb_neurons, $nb_out ){
		$instance = new self();
		for( $i=0, $maxi=$nb_neurons; $i<$maxi; $i++ ){
			$weights = xavier_init($nb_in, $nb_in, $nb_out, 'nrand');
			$bias = nrand(0,1);
			$instance->neurons[] = new Neuron( $weights, $bias );
		}
		return $instance;
	}
	
	/**
	 * Instanciate a fully connected layer from the given configuration.
	 * 
	 * @param array $conf The layer configuration.
	 * @return FullyConnectedLayer The layer
	 */
	public static function fromConf( array $conf ){
		$instance = new self();
		foreach( $conf as $n ){
			if( !is_array($n[0]) || !is_numeric($n[1]) ){
				exit("invalid configuration provided for neuron: ".var_export($n, true));
			}
			$instance->neurons[] = new Neuron( $n[0], $n[1] );
		}
		return $instance;
	}
}
