<?php
require_once 'FullyConnectedLayer.php';

class FullyConnectedNeuralNetwork {
	
	/** The number of complete cycles between 2 evaluations of the loss */
	const AUTO_TRAIN_LOSS_EVAL_FREQUENCY = 10;
	/** The number of oscillations before the learn rate is updated */
	const AUTO_TRAIN_MAX_OSCILLATIONS = 3;
	
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
	 * Feed forward once the full dataset, then perform back propagation and 
	 * update neurons.
	 * 
	 * @param array $data The training dataset.
	 * @param array $y_trues The correct outputs for the training dataset.
	 * @param number $learn_rate The learning rate.
	 * 
	 * @return array The predicted outputs for this training cycle.
	 */
	private function feedAndBack( array $data, array $y_trues, $learn_rate ){
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
		return $y_preds;
	}
	
	/**
	 * Train the network.
	 * 
	 * @param array $data The training dataset.
	 * @param array $y_trues The correct outputs for the training dataset.
	 * @param number $learn_rate The learning rate (0.1 by default). (0.1 by default).
	 * @param int $epochs The number of times the network will pass through all the dataset.
	 */
	public function train( array $data, array $y_trues, $learn_rate = 0.1, $epochs = 1000 ){
		if( count($data) != count($y_trues) ){
			exit("invalid training data provided");
		}
		
		$begin = time();
		echo date(DATE_RFC822, $begin).PHP_EOL;
		
		for( $e=1; $e<=$epochs; $e++ ){
			$y_preds = $this->feedAndBack($data, $y_trues, $learn_rate);
			
			// Calculate total loss
			if( $e%10 == 0 ){
				$loss = mse_loss($y_trues, $y_preds);
				echo "Epoch $e loss: $loss" . PHP_EOL;
			}
		}
		
		// Traning report
		echo "Training report:".PHP_EOL
				."from: ".date(DATE_RFC822, $begin).PHP_EOL
				."to: ".date(DATE_RFC822).PHP_EOL
				."learn rate: $learn_rate".PHP_EOL
				."epochs: $epochs".PHP_EOL
				."final loss: $loss".PHP_EOL;
	}
	
	/**
	 * Train the network during the given amount of time.
	 * The learn rate is automatically updated when the loss oscillates too much.
	 * 
	 * @param array $data The training dataset.
	 * @param array $y_trues The correct outputs for the training dataset.
	 * @param string $time_str The time during which the network must train.
	 * This value will be passed to strtotime() function to evaluate the duration 
	 * of the training in seconds.
	 */
	public function autoTrain( array $data, array $y_trues, $time_str = '1 hour' ){
		if( count($data) != count($y_trues) ){
			exit("invalid training data provided");
		}
		
		$begin = time();
		echo date(DATE_RFC822, $begin).PHP_EOL;
		$train_time = strtotime( $time_str, 0 );
		
		$e=0;
		$oscillations=0;
		$learn_rate=1;
		$prev_loss = 1;
		while( time()-$begin <= $train_time ){
			$e++;
			$y_preds = $this->feedAndBack($data, $y_trues, $learn_rate);
			
			// Every x epochs, update the learning rate if necessary
			if( $e%self::AUTO_TRAIN_LOSS_EVAL_FREQUENCY == 0 ){
				$loss = mse_loss($y_trues, $y_preds);
				echo "Epoch $e: LR $learn_rate, loss $loss" . PHP_EOL;
				
				if( $loss > $prev_loss && ++$oscillations >= self::AUTO_TRAIN_MAX_OSCILLATIONS ){
					$learn_rate /= 2;
					$oscillations = 0;
				}
				
				$prev_loss = $loss;
			}
		}
		
		// Traning report
		echo "Training report:".PHP_EOL
				."from: ".date(DATE_RFC822, $begin).PHP_EOL
				."to: ".date(DATE_RFC822).PHP_EOL
				."epochs: $e".PHP_EOL
				."final learn rate: $learn_rate".PHP_EOL
				."final loss: $loss".PHP_EOL;
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
