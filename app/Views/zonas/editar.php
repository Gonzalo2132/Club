<form action="
<?php 
echo base_url();

?>public/zonas/actualizar/<?php echo $zona[0]['id'];?>
" method="post">

    <label class="form-label" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" 
    placeholder="" value="<?php echo $zona[0]['denominacion'];?>">


    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/zonas
">Cancelar</a>


</form>