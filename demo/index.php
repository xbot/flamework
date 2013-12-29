<?php
require_once '../framework/Flame.php';
use org\x3f\flamework\Flame;

$_config = 'protected/config.php';

Flame::createApplication($_config)->run();
?>
