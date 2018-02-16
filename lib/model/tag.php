<?php
namespace Model;

class Tag extends \DB\Cortex {
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
           'news' => array(
                'has-many' => array('\Model\News', 'tags')
            ),
           'events' => array(
                'has-many' => array('\Model\Event', 'tags')
            ),
           'places' => array(
                'has-many' => array('\Model\Place', 'tags')
            ),
           'pages' => array(
                'has-many' => array('\Model\Page', 'tags')
            ),
           'files' => array(
                'has-many' => array('\Model\File', 'tags')
            ),
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'tag';
}

