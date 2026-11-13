<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Jugadores inscriptos: <?= esc($disciplina['nombre']) ?></h2>
    <a class="btn btn-secondary" href="<?= base_url('public/disciplinas') ?>">Volver a disciplinas</a>
</div>

<p><?= esc($disciplina['descripcion'] ?? '') ?></p>
<div class="table-responsive">
    <table class="table table-hover" id="contenido-lista">
        <thead>
            <tr>
                <th>Jugador</th>
                <th>D.N.I.</th>
                <th>Fecha de nacimiento</th>
                <th>Teléfono</th>
                <th>Tutores</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($jugadores !== []): ?>
                <?php foreach ($jugadores as $jugador): ?>
                    <tr>
                        <td><?= esc(trim($jugador['nombre'] . ' ' . $jugador['apellido'])) ?></td>
                        <td><?= esc($jugador['dni']) ?></td>
                        <td><?= !empty($jugador['fecha_nac']) ? esc(date('d/m/Y', strtotime($jugador['fecha_nac']))) : '-' ?></td>
                        <td><?= esc($jugador['telefono'] ?: '-') ?></td>
                        <td>
                            <?php if ($jugador['tutores'] !== []): ?>
                                <?php foreach ($jugador['tutores'] as $tutor): ?>
                                    <div class="mb-2">
                                        <strong><?= esc($tutor['nombre']) ?></strong>
                                        <div>D.N.I.: <?= esc($tutor['dni']) ?> · Teléfono: <?= esc($tutor['telefono']) ?></div>
                                        <div>Email: <?= esc($tutor['email'] ?: '-') ?> · Parentesco: <?= esc($tutor['parentesco'] ?: '-') ?></div>
                                    </div>
                                <?php endforeach ?>
                            <?php else: ?>
                                -
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td class="text-center" colspan="5">Todavía no hay socios inscriptos en esta disciplina.</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
