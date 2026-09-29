<form action="
<?php 
echo base_url();

?>public/liquidaciones/guardar
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" placeholder="">

    <label class="form-label" for="monto">Monto total</label>
    <input class="form-control" type="number" step="0.01" min="0" name="monto" id="monto"
        value="<?= esc($montoTotal); ?>" required>

    <label class="form-label" for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input class="form-control" type="date" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="dd/mm/AAAA">

    <fieldset class="mb-3">
        <legend class="form-label">Planes</legend>
        <p class="form-text">Seleccione los planes e indique el precio de cada uno.</p>
        <?php if (!empty($planes)) { ?>
            <?php foreach ($planes as $plan) { ?>
                <div class="row align-items-center mb-2">
                    <div class="col-md-6 form-check">
                    <input class="form-check-input" type="checkbox" id="plan-<?= esc($plan['id']); ?>"
                        name="planes[]" value="<?= esc($plan['id']); ?>"
                        <?= in_array($plan['id'], $planesSeleccionados) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="plan-<?= esc($plan['id']); ?>">
                        <?= esc($plan['nombre']); ?>
                    </label>
                    </div>
                    <div class="col-md-6">
                        <label class="visually-hidden" for="monto-plan-<?= esc($plan['id']); ?>">Precio de <?= esc($plan['nombre']); ?></label>
                        <input class="form-control monto-plan" type="number" step="0.01" min="0"
                            id="monto-plan-<?= esc($plan['id']); ?>"
                            name="montos_plan[<?= esc($plan['id']); ?>]"
                            value="<?= esc(old('montos_plan.' . $plan['id'], '0.00')); ?>"
                            placeholder="Precio del plan">
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="form-text">No hay planes disponibles.</p>
        <?php } ?>
    </fieldset>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const total = document.getElementById('monto');
        const checks = document.querySelectorAll('input[name="planes[]"]');

        function actualizarTotal() {
            const seleccionados = Array.from(checks).filter((check) => check.checked);
            let suma = 0;
            seleccionados.forEach((check) => {
                const precio = document.getElementById('monto-plan-' + check.value);
                precio.required = true;
                suma += Number(precio.value || 0);
            });
            checks.forEach((check) => {
                document.getElementById('monto-plan-' + check.value).required = check.checked;
            });
            if (seleccionados.length) {
                total.value = suma.toFixed(2);
                total.readOnly = true;
            } else {
                total.readOnly = false;
            }
        }

        checks.forEach((check) => check.addEventListener('change', actualizarTotal));
        document.querySelectorAll('.monto-plan').forEach((input) => input.addEventListener('input', actualizarTotal));
        actualizarTotal();
    });
</script>