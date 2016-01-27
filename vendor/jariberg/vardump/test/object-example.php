<?php

require __DIR__ . '/../src/Vardump.php';

$fixtures = require __DIR__ . '/_fixtures.php';

$dump = new Vardump();
$stdClass = new \stdClass();
$stdClass->time = date('r');
$stdClass->dummy = new DummyClass();
$stdClass->dummy->a_public_array = array(1,2,3);

$dump->dump($stdClass);
$dump->dump(array('stdClass'=>$stdClass, 'var_dump_instance'=>$dump));


