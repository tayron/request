<?php

require_once('./vendor/autoload.php');

$teste = new \Tayron\RequestFacede();
echo $teste->getUri();