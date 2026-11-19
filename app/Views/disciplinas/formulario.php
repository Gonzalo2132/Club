<?php $categorias = $categorias ?? []; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>

<form method="post" action="<?= base_url('public/disciplinas/' . $accion) ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label" for="nombre">Nombre</label>
        <input class="form-control" type="text" name="nombre" id="nombre" maxlength="80" required
            value="<?= esc(old('nombre', $disciplina['nombre'])) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label" for="descripcion">Descripción (opcional)</label>
        <textarea class="form-control" name="descripcion" id="descripcion" maxlength="255" rows="3"><?= esc(old('descripcion', $disciplina['descripcion'] ?? '')) ?></textarea>
    </div>

    <?php if (!empty($disciplina['id'])): ?>
        <div class="mb-3">
            <label class="form-label">Categorías de la disciplina</label>
            <?php if ($categorias !== []): ?>
                <div class="mb-2 d-flex flex-wrap gap-2">
                    <?php foreach ($categorias as $categoria): ?>
                        <span class="badge bg-secondary"><?= esc($categoria['nombre']) ?></span>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <p class="form-text mt-0">Todavía no hay categorías cargadas para esta disciplina.</p>
            <?php endif ?>
            <label class="form-label" for="nueva_categoria">Agregar categoría (opcional)</label>
            <input class="form-control" type="text" name="nueva_categoria" id="nueva_categoria" maxlength="80"
                placeholder="Ej: Primera, Reserva, Sub-15, Infantiles"
                value="<?= esc(old('nueva_categoria', '')) ?>">
        </div>
    <?php endif ?>

    <button class="btn btn-success" type="submit">Guardar</button>
    <a class="btn btn-secondary" href="<?= base_url('public/disciplinas') ?>">Cancelar</a>
</form>