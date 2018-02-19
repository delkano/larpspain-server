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
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'news';

}