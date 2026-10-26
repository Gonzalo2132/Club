<form action="
<?php
echo base_url();

?>public/socios/guardar
" method="post">

    <p class="form-text"><span class="text-danger">*</span> Los campos marcados con asterisco rojo son obligatorios.</p>

    <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50"
        placeholder="Ingrese nombre" value="<?= esc(old('nombre')) ?>" required>

    <label class="form-label" for="apellido">Apellido <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="apellido" id="apellido" maxlength="50"
        placeholder="Ingrese apellido" value="<?= esc(old('apellido')) ?>" required>

    <label class="form-label" for="domicilio">Domicilio <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="domicilio" id="domicilio" maxlength="80"
        placeholder="Ingrese un domicilio" value="<?= esc(old('domicilio')) ?>" required>

    <label class="form-label" for="telefono">Teléfono <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="telefono" id="telefono" maxlength="30"
        placeholder="Ingrese un teléfono" value="<?= esc(old('telefono')) ?>" required>

    <label class="form-label" for="fecha_nac">Fecha de Nacimiento <span class="text-danger">*</span></label>
    <input class="form-control" type="date" name="fecha_nac" id="fecha_nac" max="<?= date('Y-m-d') ?>"
        value="<?= esc(old('fecha_nac')) ?>" required>

    <label class="form-label" for="dni">D.N.I. <span class="text-danger">*</span></label>
    <input class="form-control" type="text" name="dni" id="dni" maxlength="30"
        placeholder="Ingrese DNI" value="<?= esc(old('dni')) ?>" required>

    <label class="form-label" for="email">Email</label>
    <input class="form-control" type="text" name="email" id="email" placeholder="Ingrese email">

    <label class="form-label" for="zona">Zona</label>
    <select class="form-control" id="id_zona" name="id_zona">
        <?php
        foreach ($zonas as $zona) {
            $selected = (string) old('id_zona') === (string) $zona['id'] ? ' selected' : '';
            echo '<option value="' . (int) $zona['id'] . '"' . $selected . '>' . esc($zona['denominacion']) . '</option>';
        }
        ?>
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
                        name="planes[]" value="<?= esc($plan['id']); ?>">
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