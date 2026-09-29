<form action="
<?php 
echo base_url();

?>public/liquidaciones/guardar
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="">

    <label class="form-label" for="monto">Monto</label>
    <input class="form-control" type="number" name="monto" id="monto" placeholder="">

    <label class="form-label" for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input class="form-control" type="date" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="dd/mm/AAAA">

<<<<<<< HEAD
    <fieldset class="mb-3">
        <legend class="form-label">Planes</legend>
        <p class="form-text">Seleccione uno o varios planes para esta liquidación.</p>
        <?php if (!empty($planes)) { ?>
            <?php foreach ($planes as $plan) { ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="plan-<?= esc($plan['id']); ?>"
                        name="planes[]" value="<?= esc($plan['id']); ?>"
                        <?= in_array($plan['id'], $planesSeleccionados) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="plan-<?= esc($plan['id']); ?>">
                        <?= esc($plan['nombre']); ?>
                    </label>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="form-text">No hay planes disponibles.</p>
        <?php } ?>
    </fieldset>
=======
    <label class="form-label" for="id_plan">ID del Plan</label>
    <input class="form-control" type="number" name="id_plan" id="id_plan" placeholder="-" value="<?= isset($liquidacion) ? $liquidacion[0]['id_plan'] : '' ?>">
>>>>>>> 4f2c8dac17deb4031f780a60af9ecee5dc5101ea

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