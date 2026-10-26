<a class="btn btn-secondary mb-3" href="<?= base_url('public/socios') ?>">
    <i class="bi bi-arrow-left"></i> Volver al listado
</a>

<form action="
<?php 
echo base_url();

?>public/socios/actualizar/<?php echo $socio[0]['id'];?>
" method="post">

    <p class="form-text"><span class="text-danger">*</span> Los campos marcados con asterisco rojo son obligatorios.</p>

    <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50"
        placeholder="Ingrese un nombre" value="<?= esc(old('nombre', $socio[0]['nombre'])) ?>" required>

    <label class="form-label" for="apellido">Apellido <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="apellido" id="apellido" maxlength="50"
        placeholder="Ingrese un apellido" value="<?= esc(old('apellido', $socio[0]['apellido'])) ?>" required>

    <label class="form-label" for="domicilio">Domicilio <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" maxlength="80"
        placeholder="Ingrese un domicilio" value="<?= esc(old('domicilio', $socio[0]['domicilio'])) ?>" required>

    <label class="form-label" for="telefono">Teléfono <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="telefono" id="telefono" maxlength="30"
        placeholder="Ingrese un teléfono" value="<?= esc(old('telefono', $socio[0]['telefono'])) ?>" required>

    <label class="form-label" for="fecha_nac">Fecha de Nacimiento <span class="text-danger">*</span></label>
    <input class="form-control" type="date" name="fecha_nac" id="fecha_nac" max="<?= date('Y-m-d') ?>"
    value="<?= esc(old('fecha_nac', $socio[0]['fecha_nac'])); ?>" required>

    <label class="form-label" for="dni">D.N.I. <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="dni" id="dni" maxlength="30"
        placeholder="Ingrese D.N.I" value="<?= esc(old('dni', $socio[0]['dni'])) ?>" required>

    <label class="form-label" for="email">Email</label>
    <input class="form-control" type="text" name="email" id="email" 
    placeholder="dd/mm/AAAA" value="<?php echo $socio[0]['email'];?>">

    <label class="form-label" for="zona">Zona</label>
    <select class="form-control" id="id_zona" name="id_zona">
        <?php 
        foreach($zonas as $zona){ ?>

            <option value="<?php echo $zona['id']; ?>" 
                <?php
                    if((string) old('id_zona', $socio[0]['id_zona']) === (string) $zona['id']){
                        echo 'selected';
                    }
                ?>
            ><?= esc($zona['denominacion']) ?></option>
       
        <?php }?>
    </select>

    <?= view('socios/_recaudadores_zona', [
        'recaudadores' => $recaudadores,
        'recaudadorSeleccionado' => $recaudadorSeleccionado,
    ]) ?>

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

    <?= view('socios/_disciplinas_tutor', [
        'disciplinas' => $disciplinas,
        'misDisciplinas' => $misDisciplinas,
        'tutores' => $tutores,
    ]) ?>

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/socios
">Cancelar</a>
</form>