<?php
$nombre = old('nombre', $rol['nombre']);
$seleccionados = old('modulos', $modulosSeleccionados);
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
        <legend class="fs-6">Módulos permitidos</legend>
        <?php foreach ($modulos as $key => $label): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="modulos[]" value="<?= esc($key) ?>"
                    id="modulo-<?= esc($key) ?>" <?= in_array($key, $seleccionados, true) ? 'checked' : '' ?>>
                <label class="form-check-label" for="modulo-<?= esc($key) ?>"><?= esc($label) ?></label>
            </div>
        <?php endforeach ?>
    </fieldset>

    <button class="btn btn-success" type="submit">Guardar</button>
    <a class="btn btn-secondary" href="<?= base_url('public/roles') ?>">Cancelar</a>
</form>
