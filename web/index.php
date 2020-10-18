<?php

use Symfony\Component\HttpFoundation\Request;
date_default_timezone_set('America/Bogota');

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});


//Ruta de demostración, para validar que se recibe(n) dato(s) y se responde con este mismo
$app->post('/enviarDato', function (Request $request) use ($app) {
   return $request;
});


//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/guardarDato', function (Request $request) use ($app) {

	$Voltbat = $request->get('Voltbat');
	$tabla = $request->get('tabla');

	$dbconn = pg_pconnect("host=ec2-54-152-40-168.compute-1.amazonaws.com dbname=da5l2p8fhao45b user=rvjdadbcfsozcx password=d568c86e4a84d477292656b6718984c408f607f5459bca9b6eaf550604dfcf66");

	$data = array(
		"Fecha"=>date('Y-m-d H:i:s'),
		"Voltpanel" => $request->get('Voltpanel'),
		"Voltbat" => $Voltbat
		);

	$respuesta = pg_insert($dbconn, $tabla, $data);
   	
   	return $respuesta;
});

$app->post('/arduinoenvia', function (Request $request) use ($app) {

	$VoltBat = $request->get('VoltBat');
	$VoltPanel = $request->get('VoltPanel');
	$IPanel = $request->get('IPanel');
	$Temp1 = $request->get('Temp1');
	$Temp2 = $request->get('Temp2');
	$Potencia = $request->get('Potencia');
	$tabla = $request->get('tabla');
	

	$dbconn = pg_pconnect("host=ec2-54-152-40-168.compute-1.amazonaws.com port=5432 dbname=da5l2p8fhao45b user=rvjdadbcfsozcx password=d568c86e4a84d477292656b6718984c408f607f5459bca9b6eaf550604dfcf66");

	$data = array(
		"fecha"=>date('Y-m-d H:i:s'),
		"VoltBat" => $VoltBat,
		"VoltPanel" => $VoltPanel,
		"IPanel" => $IPanel,
		"Temp1" => $Temp1,
		"Temp2" => $Temp2,
		"Potencia" => $Potencia,
		);

	$respuesta = pg_insert($dbconn, $tabla, $data);
   	
   	return $respuesta;
});


//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/postArduino', function (Request $request) use ($app) {
   	return "OK";
});

$app->run();
