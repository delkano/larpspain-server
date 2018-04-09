<?php
namespace Controller;

class User extends Readable {
    public function __construct() {
        parent::__construct("User", $blacklist=["auth_key", "timeout", "refresh_key", "password"]);
    }


    protected function processInput($vars, $obj) {
        if($obj->dry()) // New User, can be created without further ado (although we should check for roles and validation hijacking)
            return $vars;
        else // Otherwise, behave like a normal Readable object.
            return parent::processInput($vars, $obj);
    }
}
