<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear tu cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; 

?>

<form action="/crear-cuenta" method="POST" class="formulario" >
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="tu nombre" value="<?php echo s($usuario->nombre) ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" placeholder="Tu apellido" value="<?php echo s($usuario->apellido)?>">   

    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Tu Teléfono" value="<?php echo s($usuario->telefono)?>">
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo s($usuario->email)?>">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Tu password" >
    </div>
    <input type="submit" value="Crear cuenta" class="boton">
</form>


<div class="acciones">
    <a href="/">Ya tienes una cuenta? Iniciar sesión</a>
    <a href="/olvide">Olvidaste tu contraseña</a>
</div>