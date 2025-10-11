<form action="
<?php 
echo base_url();

?>public/zonas/actualizar/<?php echo $cliente[0]['id'];?>
" method="post">

    <label class="form-control" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" 
    placeholder="" value="<?php echo $cliente[0]['denominacion'];?>">


    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/zonas
">Cancelar</a>


</form>