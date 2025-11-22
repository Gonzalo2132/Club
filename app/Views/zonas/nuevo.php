<form action="
<?php 
echo base_url();

?>public/zonas/guardar
" method="post">

    <label class="form-label" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" placeholder="">

    

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/zonas
">Cancelar</a>


</form>