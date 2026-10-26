<?php
$misDisciplinas = old('disciplinas', $misDisciplinas ?? []);
if (!is_array($misDisciplinas)) {
    $misDisciplinas = [];
}
$tutores = old('tutores');
if (!is_array($tutores)) {
    $tutores = array_map(static fn(array $tutor): array => [
        'nombre' => $tutor['nombre'] ?? '',
        'apellido' => $tutor['apellido'] ?? '',
        'dni' => $tutor['dni'] ?? '',
        'telefono' => $tutor['telefono'] ?? '',
        'email' => $tutor['email'] ?? '',
        'parentesco' => $tutor['parentesco'] ?? '',
    ], $tutores ?? []);
}
while (count($tutores) < 2) {
    $tutores[] = [];
}
?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>

<fieldset class="mb-3">
    <legend class="form-label">Disciplinas</legend>
    <p class="form-text">Selecciona todas las disciplinas en las que participa el socio.</p>
    <?php if ($disciplinas !== []): ?>
        <?php foreach ($disciplinas as $disciplina): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="socio-disciplina-<?= (int) $disciplina['id'] ?>"
                    name="disciplinas[]" value="<?= (int) $disciplina['id'] ?>"
                    <?= in_array((string) $disciplina['id'], array_map('strval', $misDisciplinas), true) ? 'checked' : '' ?>>
                <label class="form-check-label" for="socio-disciplina-<?= (int) $disciplina['id'] ?>">
                    <?= esc($disciplina['nombre']) ?>
                </label>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <p class="form-text">Aún no hay disciplinas activas. <a href="<?= base_url('public/disciplinas') ?>">Gestionar disciplinas</a></p>
    <?php endif ?>
</fieldset>

<fieldset class="mb-3" id="tutor-fields" data-minimum="2">
    <legend class="form-label">Tutores responsables</legend>
    <p class="form-text">Para socios menores de 18 años, registra al menos dos tutores. Para los demás socios, este dato es opcional. Si un tutor ya está registrado para otro socio, usa el mismo DNI para vincularlo.</p>
    <div id="tutor-list">
        <?php foreach ($tutores as $index => $tutor): ?>
            <?= view('socios/_tutor_campos', ['index' => $index, 'tutor' => $tutor]) ?>
        <?php endforeach ?>
    </div>
    <button class="btn btn-outline-primary btn-sm" type="button" id="agregar-tutor">Agregar otro tutor</button>
</fieldset>

<template id="tutor-template">
    <?= view('socios/_tutor_campos', ['index' => '__INDEX__', 'tutor' => []]) ?>
</template>

<script>
(() => {
    const nacimiento = document.getElementById('fecha_nac');
    const camposTutor = document.getElementById('tutor-fields');
    const lista = document.getElementById('tutor-list');
    const plantilla = document.getElementById('tutor-template');
    const botonAgregar = document.getElementById('agregar-tutor');
    let siguienteIndice = Array.from(lista.querySelectorAll('.tutor-card'))
        .reduce((maximo, tutor) => Math.max(maximo, Number(tutor.dataset.index) + 1), 0);

    function actualizarTutor() {
        const fecha = nacimiento.value ? new Date(`${nacimiento.value}T00:00:00`) : null;
        const hoy = new Date();
        const limite = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
        const esMenor = fecha !== null && !Number.isNaN(fecha.getTime()) && fecha > limite;

        lista.querySelectorAll('.tutor-required').forEach((campo) => {
            campo.required = esMenor;
        });
    }

    function actualizarBotones() {
        const tutores = lista.querySelectorAll('.tutor-card');
        tutores.forEach((tutor) => {
            tutor.querySelector('.quitar-tutor').hidden = tutores.length <= 2;
        });
    }

    botonAgregar.addEventListener('click', () => {
        lista.insertAdjacentHTML('beforeend', plantilla.innerHTML.replaceAll('__INDEX__', String(siguienteIndice++)));
        actualizarBotones();
        actualizarTutor();
    });

    lista.addEventListener('click', (event) => {
        if (event.target.closest('.quitar-tutor')) {
            event.target.closest('.tutor-card').remove();
            actualizarBotones();
        }
    });

    nacimiento.addEventListener('change', actualizarTutor);
    actualizarBotones();
    actualizarTutor();
})();
</script>
