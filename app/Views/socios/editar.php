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

    <fieldset class="mb-3">
        <legend class="form-label">Planes de suscripción</legend>
        <p class="form-text">Seleccione uno o varios planes.</p>
        <?php if (!empty($planes)) { ?>
            <?php foreach ($planes as $plan) { ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="socio-plan-<?= esc($plan['id']); ?>"
                        name="planes[]" value="<?= esc($plan['id']); ?>"
                        <?= in_array($plan['id'], $misPlanes) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="socio-plan-<?= esc($plan['id']); ?>">
                        <?= esc($plan['nombre']); ?>
                    </label>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="form-text">No hay planes disponibles.</p>
        <?php } ?>
    </fieldset>
    <br>

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/socios
">Cancelar</a>
</form>