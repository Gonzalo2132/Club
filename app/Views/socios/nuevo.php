<form action="
<?php
echo base_url();

?>public/socios/guardar
" method="post">

    <label class="form-control" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Ingrese nombre">

    <label class="form-control" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" placeholder="Ingrese apellido">

    <label class="form-control" for="domicilio">Domicilio</label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" placeholder="Ingrese un domicilio">

    <label class="form-control" for="telefono">Teléfono</label>
    <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Ingrese un teléfono">

    <label class="form-control" for="fecha_nac">Fecha de Nacimiento</label>
    <input class="form-control" type="text" name="fecha_nac" id="fecha_nac" placeholder="dd/mm/AAAA">

    <label class="form-control" for="dni">D.N.I.</label>
    <input class="form-control" type="text" name="dni" id="dni" placeholder="Ingrese DNI">

    <label class="form-control" for="email">Email</label>
    <input class="form-control" type="text" name="email" id="email" placeholder="Ingrese email">

    <label class="form-control" for="zona">Zona</label>
    <input class="form-control" type="text" name="id_zona" id="id_zona" placeholder="Ingrese zona">


    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="


<?php
echo base_url();
?>public/socios
">Cancelar</a>

</form>