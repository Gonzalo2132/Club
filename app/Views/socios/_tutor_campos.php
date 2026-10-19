<div class="card card-body mb-3 tutor-card" data-index="<?= esc($index) ?>">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Tutor <?= is_numeric($index) ? (int) $index + 1 : '' ?></strong>
        <button class="btn btn-outline-danger btn-sm quitar-tutor" type="button" aria-label="Quitar tutor">Quitar</button>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tutores-<?= esc($index) ?>-nombre">Nombre</label>
            <input class="form-control tutor-required" type="text" name="tutores[<?= esc($index) ?>][nombre]"
                id="tutores-<?= esc($index) ?>-nombre" maxlength="50" value="<?= esc($tutor['nombre'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tutores-<?= esc($index) ?>-apellido">Apellido</label>
            <input class="form-control tutor-required" type="text" name="tutores[<?= esc($index) ?>][apellido]"
                id="tutores-<?= esc($index) ?>-apellido" maxlength="50" value="<?= esc($tutor['apellido'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tutores-<?= esc($index) ?>-dni">D.N.I.</label>
            <input class="form-control tutor-required" type="text" name="tutores[<?= esc($index) ?>][dni]"
                id="tutores-<?= esc($index) ?>-dni" maxlength="30" value="<?= esc($tutor['dni'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tutores-<?= esc($index) ?>-telefono">Teléfono</label>
            <input class="form-control tutor-required" type="tel" name="tutores[<?= esc($index) ?>][telefono]"
                id="tutores-<?= esc($index) ?>-telefono" maxlength="30" value="<?= esc($tutor['telefono'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tutores-<?= esc($index) ?>-email">Email (opcional)</label>
            <input class="form-control" type="email" name="tutores[<?= esc($index) ?>][email]"
                id="tutores-<?= esc($index) ?>-email" maxlength="80" value="<?= esc($tutor['email'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="tutores-<?= esc($index) ?>-parentesco">Parentesco (opcional)</label>
            <input class="form-control" type="text" name="tutores[<?= esc($index) ?>][parentesco]"
                id="tutores-<?= esc($index) ?>-parentesco" maxlength="40"
                placeholder="Madre, padre, tutor legal..." value="<?= esc($tutor['parentesco'] ?? '') ?>">
        </div>
    </div>
</div>
