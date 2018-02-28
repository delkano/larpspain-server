<?php
namespace Model;

class Place extends \DB\Cortex {
    protected
        $fieldConf = array(
            'name' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'description' => array(
                'type' => 'TEXT',
                'nullable' => true
            ),
            'created' => array(
                'type' => 'INT8',
                'nullable' => false
            ),
            'edited' => array(
                'type' => 'INT8',
                'nullable' => false
            ),
            'address' => array(
                'type' => 'VARCHAR256',
                'nullable' => true
            ),
            'coords' => array(
                'type' => 'VARCHAR256',
                'nullable' => true
            ),
            'link' => array(
                'type' => 'VARCHAR256',
                'nullable' => true
            ),
            'events' => array(
                'has-many' => array('\Model\Event', 'place')
            ),
            'tags' => array(
                'has-many' => array('\Model\Tag', 'places')
            ),
            'owner' => array(
                'belongs-to-one' => '\Model\User'
            ),
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'place';

	public function __construct() {
        parent::__construct();

        $this->beforeinsert(function($self) {
            $self->set("created", time());
            $self->set("owner", \Base::intance()->get("user"));
        });

        $this->beforesave(function($self) {
            if(empty($self->get("name"))) {
                \Base::instance()->error(400, "Name can not be empty");
            }
            $self->set("edited", time());
        });
    }
}

