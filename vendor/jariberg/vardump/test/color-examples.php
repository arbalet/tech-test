<?php

require __DIR__ . '/../src/Vardump.php';

$dump = new Vardump();

try {

    throw new \Exception('Something is going on here');

} catch (\Exception $e) {
    $dump->error($e->getMessage());
    $dump->info(array('something something', 'it\'s awesome indeed'));
}


