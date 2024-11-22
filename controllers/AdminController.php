<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        isAuth();
        
        $fecha =$_GET['fecha']??date('Y-m-d');
        $fechas = explode('-',$fecha);//separar la fecha de los guiones 
        

        if(!checkdate($fechas[1],$fechas[2],$fechas[0])){
            header('Location: /404');
        }; //validar la fecha

        
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " RIGHT OUTER JOIN citaServicios ";
        $consulta .= " ON citaServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citaServicios.servicioId ";
        $consulta .="WHERE fecha = '{$fecha}' ";

        $citas = AdminCita::SQL($consulta);
        

        $router->render('admin/index',[
            'nombre' => $_SESSION['nombre'] ?? '',
            'fecha'=>$fecha,            
            'citas'=>$citas
        ]);
    }

}