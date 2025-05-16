<?php
// Leer JSON enviado por fetch()
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

var_dump($input);