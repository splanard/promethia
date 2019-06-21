<?php
require_once 'FullyConnectedLayer.php';

class FullyConnectedNeuralNetwork {
	
	/** @var FullyConnectedLayer[] Hidden layers */
	private $hidden;
	/** @var FullyConnectedLayer Output layers */
	private $output;
	
	function __construct(){
		$this->hidden = array();
	}
	
	/**
	 * Provide the network with the inputs and collect the ouput, 
	 * after is has passed through all the layers.
	 * 
	 * @param array $inputs network inputs
	 * @return array network outputs
	 */
	public function feedforward( $inputs ){
		$h = $inputs;
		foreach($this->hidden as $hl){
			$h = $hl->feedforward( $h );
		}
		$o = $this->output->feedforward( $h );
		return $o;
	}
	
	/**
	 * Train the network.
	 * 
	 * @param array $data The training dataset.
	 * @param array $y_trues The correct outputs for the training dataset.
	 * @param number $learn_rate The learning rate (0.1 by default).
	 * @param int $epochs The number of times the network will pass through all the dataset.
	 */
	public function train( array $data, array $y_trues, $learn_rate = 0.1, $epochs = 1000 ){
		if( count($data) != count($y_trues) ){
			exit("invalid training data provided");
		}
		
		echo "Training begins (".date(DATE_RFC822).")".PHP_EOL;
		for( $e=1; $e<=$epochs; $e++ ){
			$y_preds = [];
			foreach($data as $ii => $input){
				//debug($this);
				
				$y_true = $y_trues[$ii];
				
				/* Array containing all the layers: the first being the first hidden, the last being the output layer */
				/* @var $layers FullyConnectedLayer[] */
				$layers = array_merge( $this->hidden, [$this->output] );
				$n = count($layers);
				
				// Feed forward and keep track of the ouputs of each layer
				$outs = [];
				for( $i=0; $i<$n; $i++ ){
					$x = $i==0 ? $input : $outs[$i-1];
					$outs[] = $layers[$i]->feedforward( $x );
				}
				$y_pred = $outs[$n-1][0];
				//echo "y_pred: $y_pred".PHP_EOL;
				$y_preds[] = $y_pred;
				
				$dL_dypred = -2 * ($y_true - $y_pred);
				//echo "dL_dypred: $dL_dypred".PHP_EOL;
				
				// Initialize dL_dout with dL_dypred at the end
				$dL_dout = array_fill( 0, $n, array() );
				$dL_dout[$n-1] = [$dL_dypred];
				
				// Foreach layer, do back propagation and fill dL_dout
				for( $i=$n-1; $i>0; $i-- ){
					$dout_din = $layers[$i]->backprop( $outs[$i-1] );
					for( $j=0,$maxj=count($dout_din); $j<$maxj; $j++ ){
						$dL_dout[$i-1][] = $dL_dout[$i][0] * $dout_din[$j];
					}
				}
				$layers[0]->backprop( $input );
				
				// Update every layer
				for( $i=0; $i<$n; $i++ ){
					$layers[$i]->update( $learn_rate, $dL_dout[$i] );
				}
			}
			
			// Calculate total loss
			if( $e%10 == 0 ){
				$loss = mse_loss($y_trues, $y_preds);
				echo "Epoch $e loss: $loss (".date(DATE_RFC822).")" . PHP_EOL;
			}
		}
	}
	
	/**
	 * Export network configuration.
	 * @return string serialized configuration.
	 */
	public function exportConf(){
		foreach($this->hidden as $hl){
			$conf[] = $hl->exportConf();
		}
		$conf[] = $this->output->exportConf();
		return serialize($conf);
	}
	
	/**
	 * Instanciate a fully connected neural network, from the given information.
	 * 
	 * @param int $input The number of inputs.
	 * @param array $hidden The number of neurons for each hidden layer. 
	 * So the size of the array will be the number of hidden layers.
	 * @param int $output The number of neurons for the output layer.
	 */
	public static function create( $input, array $hidden, $output ){
		$instance = new self();
		
		// Create hidden layers
		$nb_hidden = count($hidden);
		$neurons = array_merge( [$input], $hidden, [$output] );
		for( $i=1; $i <= $nb_hidden; $i++ ){
			$instance->hidden[] = FullyConnectedLayer::create($neurons[$i-1], $neurons[$i], $neurons[$i+1]);
		}
		
		// Create output layer
		$instance->output = FullyConnectedLayer::create($hidden[$nb_hidden-1], $output, 0);
		
		return $instance;
	}
	
	/**
	 * Instanciate a fully connected neural network from the given configuration.
	 * 
	 * @param array $conf The serialized configuration of the network.
	 * @return FullyConnectedNeuralNetwork The network
	 */
	public static function fromConf( $conf ){
		$c = unserialize( $conf );
		$o_conf = array_pop( $c );
		$hls_conf = $c;
		
		$instance = new self();
		
		foreach( $hls_conf as $hl ){
			$instance->hidden[] = FullyConnectedLayer::fromConf( $hl );
		}
		$instance->output = FullyConnectedLayer::fromConf( $o_conf );
		
		return $instance;
	}
}
