<?php

// Kickstart the framework
require_once('../vendor/autoload.php');
/** @var \Base $f3 */
$f3 = \Base::instance();

$f3->set('DEBUG',3);

// Load configuration
$f3->config('../config.ini');

// We will only ever return json
header('Content-Type: application/json');

$f3->set("ONERROR", function($f3) {
    $error = $f3->ERROR;
    if($error["code"] == 500) { print_r($error); exit; }
    $f3->status($error["code"]);
    echo(json_encode([
        "errors" => [
            [
                "status" => "".$error['code'],
                "error" => $error['status'],
                "detail" => $error['text'],
            ]
        ]
    ]));
});

// Database connection
$f3->set('DB', new DB\SQL(
    "mysql:host=$f3[DB_SERVER];port=$f3[DB_PORT];dbname=$f3[DB_NAME]",
    $f3['DB_USER'],
    $f3['DB_PASSWORD']
));
    

// Let's log the routes called
$log = new Log("calls.log");
$log->write($f3->get('VERB')." ".$f3->get('URI'));
$f3->log = $log;

$f3->route('POST @app_login: /api/login', '\Controller\Auth->login'); 
$f3->route('POST @app_logout: /api/logout', '\Controller\Auth->logout'); 
$f3->route('POST @app_refresh: /api/refresh', '\Controller\Auth->refresh_token'); 

// Check basic authorization
(new \Controller\Auth)->authorize($f3);

/** This is needed for CORS */
$f3->route('OPTIONS *', function($f3) { 
    header('Access-Control-Allow-Headers: authorization, content-type');
    $f3->status(200); 
});

// Special route: user activation (via email-supplied link)
$f3->route('GET @user_activation: /api/user/@id/activate/@key', '\Controller\User->activate');

/**
 * We delegate all the REST routes to F3-JsonAPI
 */
\JsonAPI::setup();
$f3->run();
