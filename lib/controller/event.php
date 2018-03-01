<?php
namespace Controller;

class Event extends JsonApi {
   public function __construct() {
        parent::__construct('Event');

        $this->accepted_roles = ['ADMIN'];
        $this->user_var = 'user';
        $this->role_var = 'role';
    }

    protected function processInput($vars, $obj) {
        $f3 = \Base::instance();
        if($f3->exists($this->user_var)) {
            $user = $f3->get($this->user_var);
        
            if($obj->dry()) { //New object 
                if(!in_array($user->get($this->role_var), $this->accepted_roles) || empty($vars["attributes"]['editors']))
                    $obj->editors[] = $user; // We're not an admin or we're not trying to set the owner
                else
                    foreach($vars["attributes"]['editors'] as $editor) {
                        $obj->editors[] = $editor; // TODO: change this according to the actual input used in this situations
                    }
            } else if(!$obj->editors->contains($user) && !in_array($user->get($this->role_var), $this->accepted_roles))
                $f3->error(403, "You have not the permissions required to do this.");

            return $vars;
        } else $f3->error(403, "You are not authenticated"); 
    }

}
