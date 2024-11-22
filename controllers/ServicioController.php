<?php 

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{
    public static function index(Router $router){
        isAdmin();
        if (session_status() == PHP_SESSION_NONE) { //verificar si hay una sesion activa o no
            session_start();
        }

        $servicios = Servicio::all();
        $router->render('servicios/index',[
            'nombre'=>$_SESSION['nombre'],
            'servicios'=>$servicios
        ]);
    }

    public static function crear(Router $router){
       isAdmin();
       $servicio = new Servicio();
       $alertas = [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear',[
            'nombre'=>$_SESSION['nombre'],
            'servicio' =>$servicio,
            'alertas' =>$alertas
        ]);
    }

    public static function actualizar(Router $router){
        isAdmin();
        $id = $_GET['id'];
        if(!is_numeric($id)){
            header('Location: /servicios');
            return;
        }
        $servicio = Servicio::find($_GET['id']);
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar',[
            'alertas'=>$alertas,
            'servicio'=>$servicio,
            'nombre'=>$_SESSION['nombre']
        ]);
    }

    public static function eliminar(){
        isAdmin();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $servicio=Servicio::find($_POST['id']);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}