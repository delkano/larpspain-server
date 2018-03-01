<?php
namespace Model;

class User extends \DB\Cortex {
    protected
        $fieldConf = array(
            'email' => array(
                'type' => 'VARCHAR256',
                'default' => '',
                'nullable' => false
            ),
             'password' => array(
                'type' => 'VARCHAR256',
                'default' => '',
                'nullable' => false
            ),
            'name' => array(
                'type' => 'VARCHAR256',
                'default' => '',
                'nullable' => false
            ),
           'page' => array(
                'has-many' => array('\Model\Page', 'owner')
            ),
           'file' => array(
                'has-many' => array('\Model\File', 'owner')
            ),
           'place' => array(
                'has-many' => array('\Model\Place', 'owner')
            ),
           'news' => array(
                'has-many' => array('\Model\News', 'owner')
            ),
           'events' => array(
                'has-many' => array('\Model\Event', 'editors')
            ),
            'role' => array(
                'type' => 'VARCHAR128',
                'default' => "USER",
                'nullable' => false
            ),
            'verified' => array(
                'type' => 'BOOLEAN',
                'default' => 0
            ),
            'auth_key' => array(
                'type' => 'VARCHAR128',
                'nullable' => true
            ),
            'timeout' => array(
                'type' => 'INT8',
                'nullable' => true
            ),
            'refresh_key' => array(
                'type' => 'VARCHAR128',
                'nullable' => true
            ),
            'created' => array(
                'type' => 'INT8',
                'default' => 0,
                'nullable' => false
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'user';


    // Let's sanitize and such
    /**
     * Let's encode the password when setting it
     */
    public function set_password($pass) {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

	public function __construct() {
        parent::__construct();

        $this->beforeinsert(function($self) {
            // Some input validation is only to be done on insertion
            if(empty($self->get("password"))) {
                \Base::instance()->error(400, "Password can not be empty");
            }

            $self->set("created", time());

            // refresh_key sits empty until first login, so we can use it to temporarily
            // store the activation key to be sent via email
            $self->set("refresh_key", bin2hex(openssl_random_pseudo_bytes(128)));
        });

        /* Not needed for now
        $this->beforeupdate(function($self) {
            // Some is only required during UPDATE
        });
        */

        $this->beforesave(function($self) {
            // Some must be done for every INSERT and UPDATE
            if(empty($self->get('email')) || !filter_var($self->get('email'), FILTER_VALIDATE_EMAIL)) {
                \Base::instance()->error(400, "Invalid email");
            }

            if(empty($self->get("name"))) {
                \Base::instance()->error(400, "Invalid name");
            }

            if(!in_array($self->get("role"), ['USER', 'ADMIN'])) {
                $self->set("role", "USER");
            }

        });

        // We should add the mail validation affair into the afterinsert hook
        $this->afterinsert(function($self) {
            // TODO: change mail template and subject into localizable strings
            // Many lines are temporarily commented here
            $f3 = \Base::instance();
            $link = "http".($f3["SERVER.HTTPS"]?"s":"");
            $link.= "://".$f3->get("SERVER.SERVER_NAME");
            //$link.= $f3->alias('user_activation', ['id' => $self->get("id"), 'key' => $self->get("refresh_key")]);
            $f3->link = $link;
            $to = $self->get("email");
            //$from = \Base::instance()->get("MAIL");
            $subject = "Account activation";
            // Load mail template
            //$body = \Template::instance()->render("mail.txt");
            // Send it
            //mail($to, $subject, $body, "From: $from");
        });
    }

    // Useful methods
    public function validate($password) {
        return !$this->dry()
            && $this->get("verified")
            && password_verify($password, $this->get("validated")); 
    }

    // When loading with email verification
    public function load_and_verify($id, $key) {
        return $this->load(["`id`=? AND `refresh_key`=?", $id, $key]);
    }
}

