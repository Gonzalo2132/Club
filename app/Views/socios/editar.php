<form action="
<?php 
echo base_url();

?>public/socios/actualizar/<?php echo $socio[0]['id'];?>
" method="post">

    <label class="form-control" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" 
    placeholder="Ingrese un nombre" value="<?php echo $socio[0]['nombre'];?>">

    <label class="form-control" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" 
    placeholder="Ingrese un apellido" value="<?php echo $socio[0]['apellido'];?>">

    <label class="form-control" for="domicilio">Domicilio</label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" 
    placeholder="Ingrese un domicilio" value="<?php echo $socio[0]['domicilio'];?>">

    <label class="form-control" for="telefono">Teléfono</label>
    <input class="form-control" type="text" name="telefono" id="telefono" 
    placeholder="Ingrese un teléfono" value="<?php echo $socio[0]['telefono'];?>">

    <label class="form-control" for="fecha_nac">Fecha de Nacimiento</label>
    <input class="form-control" type="date" name="fecha_nac" id="fecha_nac" 
    placeholder="dd/mm/AAAA" value="<?php echo $socio[0]['fecha_nac'];?>">

    <label class="form-control" for="dni">D.N.I</label>
    <input class="form-control" type="text" name="dni" id="dni" 
    placeholder="Ingrese D.N.I" value="<?php echo $socio[0]['dni'];?>">

    <label class="form-control" for="email">Email</label>
    <input class="form-control" type="text" name="email" id="email" 
    placeholder="dd/mm/AAAA" value="<?php echo $socio[0]['email'];?>">

    <label class="form-control" for="zona">Zona</label>
    <input class="form-control" type="text" name="id_zona" id="id_zona" 
    placeholder="dd/mm/AAAA" value="<?php echo $socio[0]['id_zona'];?>">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/socios
">Cancelar</a>
</form>