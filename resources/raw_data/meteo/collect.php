<?php
$from = explode('/', '2010/01');
$to = explode('/', '2016/12');

for( $year = $from[0]; $year <= $to[0]; $year++ ){
	$min = $year == $from[0] ? $from[1] : 1;
	$max = $year == $to[0] ? $to[1] : 12;
	for( $month = $min; $month <= $max; $month++ ){
		$period = $year.str_pad($month, 2, '0', STR_PAD_LEFT);
		echo "Collect synop.$period.csv.gz...".PHP_EOL;
		// need curl-ca-bundle.crt file in apache/bin !
		file_put_contents("synop.$period.csv.gz", fopen("https://donneespubliques.meteofrance.fr/donnees_libres/Txt/Synop/Archive/synop.$period.csv.gz", 'r'));
	}
}