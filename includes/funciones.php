

<?php


    define('TEMPLATES_URL', __DIR__ . '/templates');

    define('FUNCIONES_URL', __DIR__ . 'funciones.php');

    function incluirTemplate( string $nombre , bool $inicio =false ){
        include TEMPLATES_URL . "/$nombre.php";
    }

    function estaAutenticado(){
        // Revisamos que haya confirmacion de autenticacion
        session_start();
        if ( !$_SESSION['login'] ){
            header('Location: /');
        }

    }

    function debugear($var){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        exit;
    }

    function hola(){
        echo "Hola desde app";
    }