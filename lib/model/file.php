
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
            'date' => array(
                'type' => 'DATE',
                'nullable' => false
            ),
            'filename' => array(
                'type' => 'VARCHAR256',
                'nullable' => true
            ),
           'owner' => array(
                'belongs-to-one' => array('\Model\User')
            ),
           'tags' => array(
                'has-many' => array('\Model\Tag')
            ),
        $db = 'DB',
        $fluid = true,
        $table = 'file';
}

