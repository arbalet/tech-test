<?php

require __DIR__ . '/../src/Vardump.php';

try {
    throw new \Exception('something went wrong');
} catch (\Exception $e) {
    \Vardump::singleton()->dumpPhpException($e);
}


