
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
            'date' => array(
                'type' => 'DATE',
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
                'has-many' => array('\Model\Tag')
            ),
            'owner' => array(
                'belongs-to-one' => array('\Model\User')
            ),
        $db = 'DB',
        $fluid = true,
        $table = 'place';
}

