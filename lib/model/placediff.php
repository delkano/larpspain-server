<?php
namespace Model;

class PlaceDiff extends \DB\Cortex {
    protected
        $fieldConf = array(
            'name' => array(
                'type' => 'VARCHAR256',
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'created' => array(
                'type' => 'INT8',
            ),
            'address' => array(
                'type' => 'VARCHAR256',
            ),
            'coords' => array(
                'type' => 'VARCHAR256',
            ),
            'link' => array(
                'type' => 'VARCHAR256',
            ),
            'comment' => array(
                'type' => 'VARCHAR256',
            ),
            'owner' => array(
                'belongs-to-one' => '\Model\User'
            ),
            'place' => array(
                'belongs-to-one' => '\Model\Place'
            ),
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'placediff';

	public function __construct() {
        parent::__construct();

        $this->beforeinsert(function($self) {
            $self->set("created", time());
            $self->set("owner", \Base::intance()->get("user"));
        });
    }
}


