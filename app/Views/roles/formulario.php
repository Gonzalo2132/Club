<?php
$nombre = old('nombre', $rol['nombre']);
$seleccionados = old('permisos', $permisosSeleccionados);
if (!is_array($seleccionados)) {
    $seleccionados = [];
}
?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>

<form method="post" action="<?= base_url('public/roles/' . $accion) ?>">
    <div class="mb-3">
        <label class="form-label" for="nombre">Nombre del rol</label>
        <input class="form-control" type="text" id="nombre" name="nombre" maxlength="80" required value="<?= esc($nombre) ?>">
    </div>

    <fieldset class="mb-3">
        <legend class="fs-6">Permisos por módulo y acción</legend>
        <p class="form-text">Ver permite consultar el módulo; Crear, Editar y Eliminar habilitan esas acciones. El administrador conserva acceso completo.</p>
        <?php foreach ($modulos as $key => $label): ?>
            <?php
            $selectedActions = $seleccionados[$key] ?? [];
            if (!is_array($selectedActions)) {
                $selectedActions = [];
            }
            ?>
            <div class="card mb-2">
                <div class="card-header"><?= esc($label) ?></div>
                <div class="card-body d-flex flex-wrap gap-3">
                    <?php foreach ($acciones as $accion => $accionLabel): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="permisos[<?= esc($key) ?>][]"
                                value="<?= esc($accion) ?>"
                                id="permiso-<?= esc($key) ?>-<?= esc($accion) ?>"
                                <?= in_array($accion, $selectedActions, true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="permiso-<?= esc($key) ?>-<?= esc($accion) ?>">
                                <?= esc($accionLabel) ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endforeach ?>
    </fieldset>

    <button class="btn btn-success" type="submit">Guardar</button>
    <a class="btn btn-secondary" href="<?= base_url('public/roles') ?>">Cancelar</a>
</form>
