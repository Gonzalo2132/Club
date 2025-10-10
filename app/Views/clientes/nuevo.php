<form action="
<?php 
echo base_url();

?>public/clientes/guardar
" method="post">

    <label class="form-control" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" placeholder="Alesio Nuñez">

    <label class="form-control" for="domicilio">Domicilio</label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" placeholder="Blv. Rivadavia">

    <label class="form-control" for="email">email</label>
    <input class="form-control" type="text" name="email" id="email" placeholder="alesionunez@gmail.com">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/clientes
">Cancelar</a>


</form>