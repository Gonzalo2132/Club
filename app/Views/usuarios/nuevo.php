<form action="
<?php 
echo base_url();

?>public/usuarios/guardar
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Ingrese nombre">

    <label class="form-label" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" placeholder="Ingrese apellido">

    <label class="form-label" for="username">Nombre de Usuario</label>
    <input class="form-control" type="text" name="username" id="username" placeholder="Ingrese un nombre de usuario">

    <label class="form-label" for="clave">Clave</label>
    <input class="form-control" type="password" name="clave" id="clave" placeholder="Ingrese una Clave">

    <label class="form-label" for="dni">D.N.I.</label>
    <input class="form-control" type="text" name="dni" id="dni" placeholder="Ingrese DNI">

<br>
<br>
    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="


<?php
echo base_url();
?>public/usuarios
">Cancelar</a>

</form>