
<?php
namespace Model;

class Event extends \DB\Cortex {
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
            'date' => array(
                'type' => 'DATE',
                'nullable' => false
            ),
            'link' => array(
                'type' => 'VARCHAR256',
                'nullable' => true
            ),
           'editors' => array(
                'has-many' => array('\Model\User', 'events')
            ),
           'tags' => array(
                'has-many' => array('\Model\Tag')
            ),
           'news' => array(
                'has-many' => array('\Model\News')
            ),
           'place' => array(
                'belongs-to-one' => array('\Model\Place')
            ),
        $db = 'DB',
        $fluid = true,
        $table = 'event';
}

