<form action="
<?php 
echo base_url();

?>public/socios/actualizar/<?php echo $socio[0]['id'];?>
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" 
    placeholder="Ingrese un nombre" value="<?php echo $socio[0]['nombre'];?>">

    <label class="form-label" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" 
    placeholder="Ingrese un apellido" value="<?php echo $socio[0]['apellido'];?>">

    <label class="form-label" for="domicilio">Domicilio</label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" 
    placeholder="Ingrese un domicilio" value="<?php echo $socio[0]['domicilio'];?>">

    <label class="form-label" for="telefono">Teléfono</label>
    <input class="form-control" type="text" name="telefono" id="telefono" 
    placeholder="Ingrese un teléfono" value="<?php echo $socio[0]['telefono'];?>">

    <label class="form-label" for="fecha_nac">Fecha de Nacimiento</label>
    <input class="form-control" type="date" name="fecha_nac" id="fecha_nac" 
    placeholder="dd/mm/AAAA" value="<?php echo $socio[0]['fecha_nac'];?>">

    <label class="form-label" for="dni">D.N.I</label>
    <input class="form-control" type="text" name="dni" id="dni" 
    placeholder="Ingrese D.N.I" value="<?php echo $socio[0]['dni'];?>">

    <label class="form-label" for="email">Email</label>
    <input class="form-control" type="text" name="email" id="email" 
    placeholder="dd/mm/AAAA" value="<?php echo $socio[0]['email'];?>">

    <label class="form-label" for="zona">Zona</label>
    <select class="form-control" id="id_zona" name="id_zona">
        <?php 
        foreach($zonas as $zona){ ?>

            <option value="<?php echo $zona['id']; ?>" 
                <?php
                    if($zona['id']==$socio[0]['id_zona']){
                        echo 'selected';
                    }
                ?>
            ><?php echo $zona['denominacion']; ?></option>
       
        <?php }?>
    </select>

    <label class="form-label" for="id_plan">Plan de Suscripción</label>
    <select class="form-control" id="planes" name="planes[]" multiple style="height: 120px;">
        <option value="">Seleccione un plan...</option>
        <?php 
        foreach($planes as $plan){ ?>
            <option value="<?php echo $plan['id']; ?>"
                <?php
                    if(in_array($plan['id'], $misPlanes)){
                        echo 'selected';
                    }
                ?>
            ><?php echo $plan['nombre']; ?> ($<?php echo number_format($plan['monto'], 2, ',', '.'); ?>)</option>
        <?php } ?>
    </select>
    <br>

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/socios
">Cancelar</a>
</form>