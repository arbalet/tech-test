<?php

class DummyClass
{
    private $a_private_string = 'this is a private string';
    protected $a_protected_array = array(1,2,3);
}

return array(
    'lvl1' => array(
        'lvl2' => array(
            'lvl3' => array(
                'lvl4' => '*'
            ),
            'lvl3txt1' => '...',
            'lvl3txt2' => '...',
            'lvl3txt3' => '...',
            'lvl3txt4' => '...',
        ),
        'lvl2txt1' => '...',
        'lvl2txt2' => '...'
    ),
    'lvl1txt' => '...',
    'true' => true,
    'false' => false,
    'null' => null,
    'NULL' => null,
    'PHP_VERSION' => PHP_VERSION,
    'pi' => 3.14159265359,
    'html' => 'hello world and <span style="color:black;text-decoration:underline">hello world underlined</span>'
);


