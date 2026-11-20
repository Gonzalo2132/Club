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
        <?php
        $categoriaSeleccionada = (string) old('categoria_disciplina_id', $categoriaSeleccionada ?? '');
        ?>

        <div class="mb-3">
            <label class="form-label" for="categoria_disciplina_id">Categorías de la disciplina</label>

            <?php if ($categorias !== []): ?>
                <select class="form-select mb-2" name="categoria_disciplina_id" id="categoria_disciplina_id">
                    <option value="">Todas las categorías / Seleccionar...</option>

                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= (int) $categoria['id'] ?>"
                            <?= $categoriaSeleccionada === (string) $categoria['id'] ? 'selected' : '' ?>>
                            <?= esc($categoria['nombre']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            <?php else: ?>
                <select class="form-select mb-2" name="categoria_disciplina_id" id="categoria_disciplina_id" disabled>
                    <option value="">Todavía no hay categorías cargadas para esta disciplina</option>
                </select>
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