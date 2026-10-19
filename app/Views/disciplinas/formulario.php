<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>

<form method="post" action="<?= base_url('public/disciplinas/' . $accion) ?>">
    <div class="mb-3">
        <label class="form-label" for="nombre">Nombre</label>
        <input class="form-control" type="text" name="nombre" id="nombre" maxlength="80" required
            value="<?= esc(old('nombre', $disciplina['nombre'])) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="descripcion">Descripción (opcional)</label>
        <textarea class="form-control" name="descripcion" id="descripcion" maxlength="255" rows="3"><?= esc(old('descripcion', $disciplina['descripcion'] ?? '')) ?></textarea>
    </div>
    <button class="btn btn-success" type="submit">Guardar</button>
    <a class="btn btn-secondary" href="<?= base_url('public/disciplinas') ?>">Cancelar</a>
</form>
