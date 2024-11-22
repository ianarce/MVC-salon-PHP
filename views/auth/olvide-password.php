
<h1 class="nombre-pagina">Olvide Password</h1>

<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu email">
    </div>
    <input type="submit" value="Enviar Instrucciones" class="boton">
</form>


<div class="acciones">
    <a href="/">Ya tienes una cuenta? Iniciar sesión</a>
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crea una</a>
</div>