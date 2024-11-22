<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas=[];
       $auth=new Usuario();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);
                
                if($usuario){
                    //si el usuario se encuentra verificar la contraseña
                    if($usuario->comprobarPasswordAndToken($auth->password)){
                        //si pasa la validacion entonces autentica al usuario
                        if(empty($_SESSION)){
                              session_start();
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;


                        if($usuario->admin==="1"){
                            $_SESSION['admin'] = $usuario->admin?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                    }   
                }else{
                    Usuario::setAlerta('error','Usuario no encotrado');
                }
                
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas'=>$alertas,
            'auth'=>$auth
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');

    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario  = Usuario::where('email',$auth->email);

                if($usuario && $usuario->confirmado==="1"){
                    //si el usuario existe entonces generamos un nuevo token de un solo uso
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    $email= new Email($usuario->email,$usuario->nombre,$usuario->token);
                    
                    if($email->enviarInstrucciones()){
                        Usuario::setAlerta('exito','Revisa tu email');
                    };

                   
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                    

                }
            }

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas'=> $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $error = false;
        $alertas =[];
        $token = s($_GET['token']);
        //BUSCAR AL USUARIO CON EL TOKEN
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
            $error = true;
        }

        //SI EXISTE EL USUARIO CON EL TOKEN ASIGNADO ENTONCES LEEMOS EL FORMULARIO CON POST
            if($_SERVER['REQUEST_METHOD']==='POST'){
                $password=new Usuario($_POST);
                
                $alertas = $password->validarPassword();

                if(empty($alertas)){
                    $usuario->password = null;
                    $usuario->password = $password->password; //asignamos la nueva password de post al objeto usuario
                    $usuario->hashPassword(); //hasheamos el password
                    $usuario->token = ''; //borramos el token
                    
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /');
                    }
                }
            }

        $alertas=Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' =>$alertas,
            'error'=>$error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario;

        //Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)){
               
                //Verificar si el correo  de usuario ya esta regsitrado
                $resultado=$usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas=Usuario::getAlertas();
                }else{
                    //HASHEAR EL PASSWORD
                    $usuario->hashPassword();
                    //Generar un Token Unico
                    $usuario->crearToken();
                    //Enviar el email con el token
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();
                    
                    $resultado = $usuario->guardar();
                    if($resultado){
                        echo 'creado';
                        header('Location: /mensaje');
                    }
                }
                
            }
    
        }
        
        $router->render('auth/crearCuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];
        //Sanitizar token del metodo get;
        $token = s($_GET['token']);
        //funcion para realizar consulta a la BD y obtener el usuario con el token consultado
        $usuarioEncontrado = Usuario::where('token',$token);
        
        if(empty($usuarioEncontrado)){
            //MOSTRAR MENSAJE DE ERROR si no se encontro un usuario con el token consultado;
            Usuario::setAlerta('error','Token no valido');
        }else{
            //Modificar a usuario confirmado
            $usuarioEncontrado->confirmado = "1";
            //Eliminar token
            $usuarioEncontrado->token='';
            //ACTUALIZAR EL USUARIO EN LA BD
            $usuarioEncontrado->guardar();
            Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas' =>$alertas
        ]);
    }
}