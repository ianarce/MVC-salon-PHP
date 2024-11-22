<?php
namespace Controllers;

use Model\Cita;
use MVC\Router;

class citaController{

    public static function index(Router $router){
        isAuth();
        if(!$_SESSION){
            session_start();
        }
       
        $nombre = $_SESSION['nombre'];
        $id = $_SESSION['id'];
        $router->render('cita/index',[
            'nombre'=>$nombre,
            'id'=>$id
        ]);
    }

    
}