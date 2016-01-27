<?php

require __DIR__ . '/../src/Vardump.php';

$fixtures = require __DIR__ . '/_fixtures.php';

$dump = new Vardump();

// allow html display
$dump->setHtmlMode(true);

$dump->dump($fixtures);
