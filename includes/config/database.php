<?php

function conectarDB() : mysqli {
    
    
    $db = new mysqli('localhost','root','root','ResuMe');

    if (!$db){
        echo "Error base de datos";
        exit;
    }


    return $db;
}