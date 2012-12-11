<?php

//$dir = dir('../../');

$testFile = file_get_contents('../../app/Application.php');

$tokens = token_get_all($testFile);

$classes = array();

foreach($tokens as $key => $token) {

    if($token[0] == T_CLASS) {
        $className = null;
        $i = $key;
        while(is_null($className)) {
            $i++;
            if($tokens[$i][0] == T_STRING) {
                $className = $tokens[$i][1];
            }
        }
        $classes[] = $className;
    }
}

var_dump($classes);