<?php
namespace Model;

class News extends \DB\Cortex {
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
            'date' => array(
                'type' => 'DATE',
                'nullable' => false
            ),
           'owner' => array(
                'belongs-to-one' => '\Model\User'
            ),
           'tags' => array(
                'has-many' => array('\Model\Tag', 'news')
            ),
            'created' => array(
                'type' => 'INT8'
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'news';

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
            if(empty($self->get("date"))) {
                \Base::instance()->error(400, "Date can not be empty");
            }
        });
    }
}
