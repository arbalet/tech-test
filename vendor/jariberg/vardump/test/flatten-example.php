<?php

require __DIR__ . '/../src/Vardump.php';

$dump = new Vardump();
$dump->setFlattenMode(true);
$dump->dump(array('this is line 1', 'this is line 2'));
$dump->info('this is another line', '<span style="text-decoration:underline">this is the last line</span>');

