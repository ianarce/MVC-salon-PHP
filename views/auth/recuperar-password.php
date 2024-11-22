<h1>Recupera tu password</h1>

<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>
<?php include_once __DIR__ . '/../templates/alertas.php'?>

<?php if($error)return?>

<form method="POST" class="formulario"> <?php //NO SE LE PONE EL ACTION PARA NO PERDER EL TOKEN DE LA URL CON EL GET?>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Ingresa tu nuevo password">
       
    </div>
    <input type="submit" class="boton" value="Guardar Nuevo Password">

    <div class="acciones">
        <a href="/">Ya tienes una cuenta?Inicia sesión</a>
        <a href="/crear-cuenta">Aun no tienes una cuenta? Crea una</a>
    </div>
</form>