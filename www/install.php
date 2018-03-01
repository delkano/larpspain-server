<?php
// Kickstart the framework
require_once('../vendor/autoload.php');
/** @var \Base $f3 */
$f3 = \Base::instance();

$f3->set('DEBUG',3);

// Load configuration
$f3->config('../config.ini');

// Database connection
$f3->set('DB', new DB\SQL(
    "mysql:host=$f3[DB_SERVER];port=$f3[DB_PORT];dbname=$f3[DB_NAME]",
    $f3['DB_USER'],
    $f3['DB_PASSWORD']
));

$models = $f3->models;
foreach($models as $model) {
    echo "<li>Creating the $model table</li>";
    $class = "\Model\\$model";
    $class::setdown();
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
 
echo "<li>Admin user created</li>";
