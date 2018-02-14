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
if(file_exists($f3->get("DB_NAME")))  {
    $f3->set('DB', new \DB\SQL('sqlite:'.$f3->get('DB_NAME')));
} else { //DB creation
    $f3->set('DB', new \DB\SQL('sqlite:'.$f3->get('DB_NAME')));
    $models = $f3->models;
    foreach($models as $model) {
        echo "Creating the $model table";
        $class = "\Model\\$model";
        $class::setup();
    }
    // Let's create an admin user
    $admin = new \Model\User;
    $admin->name = "Administrator";
    $admin->email = "admin@admin.now";
    $admin->password = password_hash("admin", PASSWORD_DEFAULT);
    $admin->role = "ADMIN";
    $admin->created = time();
    $admin->verified = true;
    $admin->save();
}


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
