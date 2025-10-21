<form action="
<?php 
echo base_url();

?>public/cajas/actualizar/<?php echo $caja[0]['id'];?>
" method="post">

    <label class="form-control" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" 
    placeholder="" value="<?php echo $caja[0]['denominacion'];?>">

    <label class="form-control" for="codigointerno">Codigo Interno</label>
    <input class="form-control" type="text" name="codigointerno" id="codigointerno" 
    placeholder="" value="<?php echo $caja[0]['codigointerno'];?>">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/cajas
">Cancelar</a>


</form>