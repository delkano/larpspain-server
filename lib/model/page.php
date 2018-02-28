<?php
namespace Model;

class Page extends \DB\Cortex {
    protected
        $fieldConf = array(
            'title' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'content' => array(
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
            'owner' => array(
                'belongs-to-one' => '\Model\User'
            ),
            'tags' => array(
                'has-many' => array('\Model\Tag', 'pages')
            ),
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'page';

	public function __construct() {
        parent::__construct();

        $this->beforeinsert(function($self) {
            $self->set("created", time());
            $self->set("owner", \Base::intance()->get("user"));
        });

        $this->beforesave(function($self) {
            if(empty($self->get("title"))) {
                \Base::instance()->error(400, "Title can not be empty");
            }
            $self->set("edited", time());
        });
    }
}

