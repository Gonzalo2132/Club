<form action="
<?php 
echo base_url();

?>public/liquidaciones/guardar
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="">

    <label class="form-label" for="monto">Monto</label>
    <input class="form-control" type="number" name="monto" id="monto" placeholder="">

    <label class="form-label" for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input class="form-control" type="date" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="dd/mm/AAAA">

    <label class="form-label" for="id_plan">ID del Plan</label>
    <input class="form-control" type="number" name="id_plan" id="id_plan" placeholder="Dejar vacío si no aplica" value="<?= isset($liquidacion) ? $liquidacion[0]['id_plan'] : '' ?>">

    <label class="form-label" for="nombre_plan">Nombre del Plan</label>
    <input class="form-control" type="text" name="nombre_plan" id="nombre_plan" placeholder=" Plan Básico " value="<?= isset($liquidacion) ? $liquidacion[0]['nombre_plan'] : '' ?>">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/liquidaciones
">Cancelar</a>


</form>