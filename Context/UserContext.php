<?php
namespace Context;

//include('lib/model/user.php');
require_once('vendor/autoload.php');

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

class UserContext implements Context
{
    private function dbConnection(){
        /** @var \Base $f3 */
        $f3 = \Base::instance();

        $f3->set('DEBUG',3);

        // Load configuration
        $f3->config('config_test.ini');

        // Database connection
        $f3->set('DB', new \DB\SQL(
            "mysql:host=$f3[DB_SERVER];port=$f3[DB_PORT];dbname=$f3[DB_NAME]",
            $f3['DB_USER'],
            $f3['DB_PASSWORD']
        ));

        //$f3->run();
    }

    /**
     * @Given /^there are the following users in the database:$/
     * @param TableNode $table
     */
    public function thereAreTheFollowingUsersInTheDatabase(TableNode $table)
    {
        $this->dbConnection();

        foreach ( $table as $line ) {
            $user = new \Model\User();

            $user->set('name',$line['Name']);
            $user->set('email',$line['Email']);
            $user->set_password($line['Password']);

            $user->save();
        }

    }
}
