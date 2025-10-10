<form action="
<?php 
echo base_url();

?>public/clientes/actualizar/<?php echo $cliente[0]['id'];?>
" method="post">

    <label class="form-control" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" 
    placeholder="Alesio Nuñez" value="<?php echo $cliente[0]['denominacion'];?>">

    <label class="form-control" for="domicilio">Domicilio</label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" 
    placeholder="Blv. Rivadavia" value="<?php echo $cliente[0]['domicilio'];?>">

    <label class="form-control" for="email">email</label>
    <input class="form-control" type="text" name="email" id="email" 
    placeholder="alesionunez@gmail.com" value="<?php echo $cliente[0]['email'];?>">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/clientes
">Cancelar</a>


</form>