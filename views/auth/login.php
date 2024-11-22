<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo s($auth->email)?>">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Tu password">
    </div>
    <div class="boton-con"><input type="submit" class="boton" value="Iniciar sesión"> </div>
    
</form>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crea una</a>
    <a href="/olvide">Olvidaste tu contraseña</a>
</div>