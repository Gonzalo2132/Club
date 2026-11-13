<form action="
<?php 
echo base_url();

?>public/cajas/guardar
" method="post">

    <label class="form-label" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" placeholder="">

    <label class="form-label" for="descripcion">Descripcion</label>
    <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="">

    <label class="form-label" for="codigointerno">Codigo Interno</label>
    <input class="form-control" type="text" name="codigointerno" id="codigointerno" placeholder="">


    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/cajas
">Cancelar</a>


</form>