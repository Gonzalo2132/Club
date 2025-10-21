<form action="
<?php 
echo base_url();

?>public/recaudadores/actualizar/<?php echo $recaudador[0]['id'];?>
" method="post">

    <label class="form-control" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" 
    placeholder="Ingrese un nombre" value="<?php echo $recaudador[0]['nombre'];?>">

    <label class="form-control" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" 
    placeholder="Ingrese un apellido" value="<?php echo $recauddador[0]['apellido'];?>">

    <label class="form-control" for="dni">D.N.I.</label>
    <input class="form-control" type="text" name="dni" id="dni" 
    placeholder="Ingrese D.N.I." value="<?php echo $recaudador[0]['dni'];?>">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/recaudadores
">Cancelar</a>
</form>