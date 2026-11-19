<form action="<?= base_url('public/socios/guardar') ?>" method="post">

    <p class="form-text"><span class="text-danger">*</span> Los campos marcados con asterisco rojo son obligatorios.</p>

    <div class="mb-3">
        <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50"
            placeholder="Ingrese nombre" value="<?= esc(old('nombre')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="apellido">Apellido <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="apellido" id="apellido" maxlength="50"
            placeholder="Ingrese apellido" value="<?= esc(old('apellido')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="domicilio">Domicilio <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="domicilio" id="domicilio" maxlength="80"
            placeholder="Ingrese un domicilio" value="<?= esc(old('domicilio')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="telefono">Teléfono <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="telefono" id="telefono" maxlength="30"
            placeholder="Ingrese un teléfono" value="<?= esc(old('telefono')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="fecha_nac">Fecha de Nacimiento <span class="text-danger">*</span></label>
        <input class="form-control" type="date" name="fecha_nac" id="fecha_nac" max="<?= date('Y-m-d') ?>"
            value="<?= esc(old('fecha_nac')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="dni">D.N.I. <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="dni" id="dni" maxlength="30"
            placeholder="Ingrese DNI" value="<?= esc(old('dni')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" name="email" id="email" maxlength="80"
            placeholder="Ingrese email" value="<?= esc(old('email')) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label" for="id_zona">Zona</label>
        <select class="form-select" id="id_zona" name="id_zona">
            <option value="">Seleccione una zona...</option>
            <?php foreach ($zonas as $zona): ?>
                <?php $selected = (string) old('id_zona') === (string) $zona['id'] ? ' selected' : ''; ?>
                <option value="<?= (int) $zona['id'] ?>"<?= $selected ?>><?= esc($zona['denominacion']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <?= view('socios/_recaudadores_zona', [
            'recaudadores' => $recaudadores,
            'recaudadorSeleccionado' => $recaudadorSeleccionado,
        ]) ?>
    </div>

    <fieldset class="mb-3">
        <legend class="form-label fs-6 fw-semibold">Planes de suscripción</legend>
        <p class="form-text mt-0">Seleccione uno o varios planes.</p>
        <?php if (!empty($planes)): ?>
            <?php foreach ($planes as $plan): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="socio-plan-<?= esc($plan['id']) ?>"
                        name="planes[]" value="<?= esc($plan['id']) ?>">
                    <label class="form-check-label" for="socio-plan-<?= esc($plan['id']) ?>">
                        <?= esc($plan['nombre']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="form-text">No hay planes disponibles.</p>
        <?php endif; ?>
    </fieldset>

    <div class="mb-4">
        <?= view('socios/_disciplinas_tutor', [
            'disciplinas' => $disciplinas,
            'misDisciplinas' => $misDisciplinas,
            'tutores' => $tutores,
        ]) ?>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar</button>
        <a class="btn btn-danger" href="<?= base_url('public/socios') ?>">Cancelar</a>
    </div>

</form>