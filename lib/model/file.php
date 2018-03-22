<?php
namespace Model;

class File extends \DB\Cortex {
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
                'nullable' => false,
                'default' => 0,
            ),
            'filename' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
           'owner' => array(
                'belongs-to-one' => '\Model\User'
            ),
           'tags' => array(
                'has-many' => array('\Model\Tag', 'files')
            ),
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'file';

	public function __construct() {
        parent::__construct();

        $this->beforeinsert(function($self) {
            $self->set("created", time());
        });

        $this->beforesave(function($self) {
            if(empty($self->get("name"))) {
                \Base::instance()->error(400, "Name can not be empty");
            }
        });
    }
}

