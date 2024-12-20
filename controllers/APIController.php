<?php 
    

    namespace Controllers;

use Model\CitaServicio;
use Model\Servicio;
use Model\Cita;

    class APIController{

        public static function index(){
            $servicios = Servicio::all();
            $servicios=json_encode($servicios);
            echo $servicios;
        }

        public static function guardar(){
            //Almacena la cita y devuelve el ID
            $cita = new Cita($_POST);
            $resultado = $cita->guardar();
            $id = $resultado['id'];
            //Almacena la cita y el servicio
            $idServicios = explode(",",$_POST['servicios']);

            //
            foreach($idServicios as $idServicio){
                $args = [
                    'citaID' =>$id,
                    'servicioId'=>$idServicio
                ];
                $citaServicio = new CitaServicio($args);
                $citaServicio->guardar();
                
            }
           
            echo json_encode(['resultado'=>$resultado]);
        }

        public static function eliminar(){
            if($_SERVER['REQUEST_METHOD']==='POST'){
                $id = $_POST['id'];

                $cita = Cita::find($id);
                $cita->eliminar();
                
                header('Location:'. $_SERVER['HTTP_REFERER']); //rredirecionar a la pagina previa
            }
        }
    }

