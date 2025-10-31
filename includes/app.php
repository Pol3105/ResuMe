<?php


    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/../class/Business.php';
    require_once __DIR__ . '/../class/Reviews.php';
    require_once __DIR__ . '/funciones.php';
    require_once __DIR__ . '/config/huggingface.php';

    // Conexión a la base de datos
    $db = conectarDB();

    // Configurar clases para usar la conexión
    Business::setDB($db);
    Reviews::setDB($db);
