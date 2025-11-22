<form action="
<?php 
echo base_url();

?>public/liquidaciones/actualizar/<?php echo $liquidacion[0]['id'];?>
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" 
    placeholder="" value="<?php echo $liquidacion[0]['nombre'];?>">

    <label class="form-label" for="monto">Monto</label>
    <input class="form-control" type="text" name="monto" id="monto" 
    placeholder="" value="<?php echo $liquidacion[0]['monto'];?>">

    <label class="form-label" for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input class="form-control" type="date" name="fecha_vencimiento" id="fecha_vencimiento" 
    placeholder="dd/mm/AAAA" value="<?php echo $liquidacion[0]['fecha_vencimiento'];?>">



    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/liquidaciones
">Cancelar</a>


</form>