<form action="
<?php 
echo base_url();

?>public/cajas/guardar
" method="post">

    <label class="form-control" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" placeholder="">4

    <label class="form-control" for="descripcion">Descripcion</label>
    <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="">

    <label class="form-control" for="codigointerno">Codigo Interno</label>
    <input class="form-control" type="text" name="codigointerno" id="codigointerno" placeholder="">


    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/cajas
">Cancelar</a>


</form>