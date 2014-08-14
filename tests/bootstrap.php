<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require_once __DIR__.'/../vendor/autoload.php';

$loader->addPsr4('FluentTraversable\\', __DIR__.'/FluentTraversable');