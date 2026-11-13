<div class="card card-body mb-3">
    <h6 class="mb-2">Recaudador asignado</h6>
    <label class="form-label" for="id_recaudador">Selecciona quién atenderá al socio</label>
    <select class="form-select mb-2" id="id_recaudador" name="id_recaudador">
        <option value="">Sin recaudador asignado</option>
        <?php foreach ($recaudadores as $recaudador): ?>
            <option value="<?= (int) $recaudador['id'] ?>"
                data-id-zona="<?= (int) $recaudador['id_zona'] ?>"
                <?= (string) ($recaudadorSeleccionado ?? '') === (string) $recaudador['id'] ? 'selected' : '' ?>>
                <?= esc($recaudador['apellido'] . ', ' . $recaudador['nombre']) ?>
            </option>
        <?php endforeach ?>
    </select>
    <p class="form-text mb-2">Solo se pueden asignar recaudadores activos de la zona elegida.</p>
    <h6 class="mb-2">Recaudadores disponibles en la zona</h6>
    <div id="recaudadores-zona" aria-live="polite">
        <p class="text-muted mb-0" data-mensaje-zona>Selecciona una zona para ver sus recaudadores.</p>
        <?php foreach ($recaudadores as $recaudador): ?>
            <div class="border rounded p-2 mb-2 recaudador-zona-item"
                data-id-zona="<?= (int) $recaudador['id_zona'] ?>" hidden>
                <strong><?= esc($recaudador['apellido'] . ', ' . $recaudador['nombre']) ?></strong>
                <span class="text-muted"> — DNI: <?= esc($recaudador['dni']) ?></span>
                <div>Teléfono: <?= esc($recaudador['telefono']) ?></div>
                <?php if (!empty($recaudador['caja_nombre'])): ?>
                    <div>Caja: <?= esc($recaudador['caja_nombre']) ?></div>
                <?php endif ?>
            </div>
        <?php endforeach ?>
    </div>
</div>

<script>
(() => {
    const zona = document.getElementById('id_zona');
    const seleccion = document.getElementById('id_recaudador');
    const contenedor = document.getElementById('recaudadores-zona');
    if (!zona || !seleccion || !contenedor) {
        return;
    }

    const recaudadores = Array.from(contenedor.querySelectorAll('.recaudador-zona-item'));
    const opciones = Array.from(seleccion.querySelectorAll('option[data-id-zona]'));
    const mensaje = contenedor.querySelector('[data-mensaje-zona]');

    function actualizarRecaudadores() {
        const idZona = zona.value;
        let encontrados = 0;
        let seleccionValida = seleccion.value === '';

        opciones.forEach((opcion) => {
            const coincide = idZona !== '' && opcion.dataset.idZona === idZona;
            opcion.hidden = !coincide;
            opcion.disabled = !coincide;
            if (coincide) {
                encontrados++;
                if (opcion.value === seleccion.value) {
                    seleccionValida = true;
                }
            }
        });

        recaudadores.forEach((recaudador) => {
            const coincide = idZona !== '' && recaudador.dataset.idZona === idZona;
            recaudador.hidden = !coincide;
        });

        if (!seleccionValida) {
            seleccion.value = '';
        }
        if (idZona === '') {
            mensaje.textContent = 'Selecciona una zona para ver sus recaudadores.';
        } else if (encontrados === 0) {
            mensaje.textContent = 'No hay recaudadores activos asignados a esta zona.';
        } else {
            mensaje.textContent = '';
        }
        mensaje.hidden = idZona !== '' && encontrados > 0;
    }

    zona.addEventListener('change', actualizarRecaudadores);
    actualizarRecaudadores();
})();
</script>
