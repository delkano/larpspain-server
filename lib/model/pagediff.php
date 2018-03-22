<?php
namespace Model;

class PageDiff extends \DB\Cortex {
    protected
        $fieldConf = array(
            'title' => array(
                'type' => 'VARCHAR256',
                'nullable' => true
            ),
            'content' => array(
                'type' => 'TEXT',
                'nullable' => true
            ),
            'created' => array(
                'type' => 'INT8',
                'nullable' => false,
                'default' => 0,
            ),
            'page' => array(
                'belongs-to-one' => '\Model\Page'
            ),
            'owner' => array(
                'belongs-to-one' => '\Model\User'
            ),
            'comment' => array(
                'type' => 'VARCHAR256',
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'pagediff';

	public function __construct() {
        parent::__construct();

        $this->beforeinsert(function($self) {
            $self->set("created", time());
            $self->set("owner", \Base::intance()->get("user"));
        });
    }
}

