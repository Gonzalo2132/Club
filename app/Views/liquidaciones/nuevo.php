<form action="
<?php 
echo base_url();

?>public/liquidaciones/guardar
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="">

    <label class="form-label" for="monto">Monto</label>
    <input class="form-control" type="number" step="0.01" min="0" name="monto" id="monto"
        value="<?= esc($montoTotal); ?>" required>

    <label class="form-label" for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input class="form-control" type="date" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="dd/mm/AAAA">

    <label class="form-label" for="id_plan">Plan</label>
    <select class="form-control" id="id_plan" name="id_plan">
        <option value="">Sin plan</option>
        <?php foreach ($planes as $plan) { ?>
            <option value="<?= esc($plan['id']); ?>"
                <?= (string) $planSeleccionado === (string) $plan['id'] ? 'selected' : ''; ?>>
                <?= esc($plan['nombre']); ?>
            </option>
        <?php } ?>
    </select>

    <?php if (session()->getFlashdata('error')) { ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')); ?></div>
    <?php } ?>

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/liquidaciones
">Cancelar</a>


</form>