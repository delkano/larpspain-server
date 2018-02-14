
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
                'has-many' => array('\Model\News')
            ),
           'events' => array(
                'has-many' => array('\Model\Event')
            ),
           'places' => array(
                'has-many' => array('\Model\Place')
            ),
           'pages' => array(
                'has-many' => array('\Model\Page')
            ),
           'files' => array(
                'has-many' => array('\Model\File')
            ),
        $db = 'DB',
        $fluid = true,
        $table = 'tag';
}

