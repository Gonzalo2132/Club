<form action="<?php echo base_url(); ?>public/recaudadores/guardar" method="post">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif ?>

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50"
        placeholder="Ingrese nombre" value="<?= esc(old('nombre')) ?>" required>

    <label class="form-label" for="apellido">Apellido</label>
    <input class="form-control" type="text" name="apellido" id="apellido" maxlength="50"
        placeholder="Ingrese apellido" value="<?= esc(old('apellido')) ?>" required>

    <label class="form-label" for="dni">D.N.I.</label>
    <input class="form-control" type="text" name="dni" id="dni" maxlength="30"
        placeholder="Ingrese DNI" value="<?= esc(old('dni')) ?>" required>

    <label class="form-label" for="telefono">Teléfono</label>
    <input class="form-control" type="text" name="telefono" id="telefono" maxlength="30"
        placeholder="Ingrese número de teléfono" value="<?= esc(old('telefono')) ?>" required>

    <p class="form-text mt-2">Se creará automáticamente una caja con el nombre y apellido del recaudador.</p>

    <label class="form-label mt-3" for="id_zona">Zona</label>
    <select class="form-control" id="id_zona" name="id_zona">
        <option value="">Sin zona asignada</option>
        <?php foreach ($zona as $zonaActual) : ?>
            <option value="<?= $zonaActual['id'] ?>" <?= (string) old('id_zona') === (string) $zonaActual['id'] ? 'selected' : '' ?>>
                <?= esc($zonaActual['denominacion']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="mt-3">
        <input class="btn btn-success" type="submit" value="Guardar">
        <a class="btn btn-danger" href="<?php echo base_url(); ?>public/recaudadores">Cancelar</a>
    </div>

</form>