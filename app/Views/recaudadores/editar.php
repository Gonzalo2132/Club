<form action="
<?php
echo base_url();

?>public/recaudadores/actualizar/<?php echo $recaudador[0]['id']; ?>
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre"
        placeholder="Ingrese un nombre" value="<?php echo $recaudador[0]['nombre']; ?>">

    <label class="form-label" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido"
        placeholder="Ingrese un apellido" value="<?php echo $recaudador[0]['apellido']; ?>">

    <label class="form-label" for="dni">D.N.I</label>
    <input class="form-control" type="text" name="dni" id="dni"
        placeholder="Ingrese D.N.I" value="<?php echo $recaudador[0]['dni']; ?>">

    <label class="form-label" for="telefono">Teléfono</label>
    <input class="form-control" type="text" name="telefono" id="telefono" 
    placeholder="Ingrese número de teleófono " value="<?php echo $recaudador[0]['telefono']; ?>">

    <label class="form-label" for="caja">Zona</label>
    <select class="form-control" id="id_caja" name="id_caja">
        <?php 
        foreach($caja as $cajas){ ?>

            <option value="<?php echo $cajas['id']; ?>" 
                <?php
                    if($cajas['id']==$recaudador[0]['id_caja']){
                        echo 'selected';
                    }
                ?>
            ><?php echo $cajas['denominacion']; ?></option>
       
        <?php }?>
    </select>



    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/recaudadores
">Cancelar</a>
</form>