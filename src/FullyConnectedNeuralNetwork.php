<?php
require_once 'FullyConnectedLayer.php';

class FullyConnectedNeuralNetwork {
	
	/** @var array Hidden layers */
	private $hidden = array();
	private $output;
	
	/**
	 * Instanciate a full y connected neural network.
	 * 
	 * @param int $input The number of inputs.
	 * @param array $hidden The number of neurons for each hidden layer. 
	 * So the size of the array will be the number of hidden layers.
	 * @param int $output The number of neurons for the output layer.
	 */
	function __construct( $input, array $hidden, $output ){
		// Create hidden layers
		$nb_hidden = count($hidden);
		$neurons = array_merge( [$input], $hidden, [$output] );
		for( $i=1; $i <= $nb_hidden; $i++ ){
			$this->hidden[] = new FullyConnectedLayer($neurons[$i-1], $neurons[$i], $neurons[$i+1]);
		}
		
		// Create output layer
		$this->output = new FullyConnectedLayer($hidden[$nb_hidden-1], $output, 0);
	}
	
	public function feedforward( $inputs ){
		$h = $inputs;
		foreach($this->hidden as $hl){
			$h = $hl->feedforward( $h );
		}
		$o = $this->output->feedforward( $h );
		return $o;
	}
	
	public function train( array $data, array $y_trues, $learn_rate = 0.1, $epochs = 1000 ){
		// TODO: throw an error if count($data) != count($y_trues)
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
}
