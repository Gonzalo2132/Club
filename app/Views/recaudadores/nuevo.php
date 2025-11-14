<form action="
<?php 
echo base_url();

?>public/recaudadores/guardar
" method="post">

    <label class="form-control" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Ingrese nombre">

    <label class="form-control" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" placeholder="Ingrese apellido">

    <label class="form-control" for="dni">D.N.I.</label>
    <input class="form-control" type="text" name="dni" id="dni" placeholder="Ingrese DNI">

    <label class="form-control" for="telefono">Teléfono</label>
    <input class="form-control" type="text" name="telefono" id="telefono" placeholder="Ingrese número de teleófono ">

    <label class="form-control" for="caja">caja</label>
    <select class="form-control" id="id_caja" name="id_caja">
        <?php
        foreach ($caja as $cajas) {
            echo '<option value="' . $cajas['id'] . '">' . $cajas['denominacion'] . '</option>';
        }
        ?>
    </select>


    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="


<?php
echo base_url();
?>public/recaudadores
">Cancelar</a>

</form>