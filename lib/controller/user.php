<?php
namespace Controller;

class User extends Readable {
    public function __construct() {
        parent::__construct("User", $blacklist=["auth_key", "timeout", "refresh_key", "password"]);
    }
}
