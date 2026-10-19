<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Vista previa del socio</h2>
    <div>
        <a class="btn btn-warning" href="<?= base_url('public/socios/editar/' . $socio['id']); ?>">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a class="btn btn-info" href="<?= base_url('public/liquidaciones/listado_socio/' . $socio['id']); ?>">
            <i class="bi bi-cash-stack"></i> Ver pagos
        </a>
        <a class="btn btn-secondary" href="<?= base_url('public/socios'); ?>">Volver</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Información personal</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <strong>Nombre completo:</strong>
                <?= esc(trim(($socio['nombre'] ?? '') . ' ' . ($socio['apellido'] ?? ''))); ?>
            </div>
            <div class="col-md-6 mb-2"><strong>D.N.I.:</strong> <?= esc($socio['dni'] ?: '-'); ?></div>
            <div class="col-md-6 mb-2"><strong>Fecha de nacimiento:</strong>
                <?= !empty($socio['fecha_nac']) ? esc(date('d/m/Y', strtotime($socio['fecha_nac']))) : '-'; ?>
            </div>
            <div class="col-md-6 mb-2"><strong>Teléfono:</strong> <?= esc($socio['telefono'] ?: '-'); ?></div>
            <div class="col-md-6 mb-2"><strong>Email:</strong> <?= esc($socio['email'] ?: '-'); ?></div>
            <div class="col-md-6 mb-2"><strong>Domicilio:</strong> <?= esc($socio['domicilio'] ?: '-'); ?></div>
            <div class="col-md-6 mb-2"><strong>Zona:</strong> <?= esc($zona['denominacion'] ?? '-'); ?></div>
            <div class="col-md-6 mb-2"><strong>Fecha de alta:</strong>
                <?= !empty($socio['fecha_alta']) ? esc(date('d/m/Y', strtotime($socio['fecha_alta']))) : '-'; ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Disciplinas</div>
    <div class="card-body">
        <?php if ($disciplinas): ?>
            <?php foreach ($disciplinas as $disciplina): ?>
                <span class="badge bg-success text-white me-1"><?= esc($disciplina['nombre']); ?></span>
            <?php endforeach ?>
        <?php else: ?>
            <span>El socio no está inscripto en disciplinas.</span>
        <?php endif ?>
    </div>
</div>

<?php if ($tutores): ?>
<div class="card mb-4">
    <div class="card-header">Tutores responsables</div>
    <div class="card-body row">
        <?php foreach ($tutores as $tutor): ?>
            <div class="col-lg-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <strong><?= esc(trim($tutor['nombre'] . ' ' . $tutor['apellido'])); ?></strong>
                    <div><strong>D.N.I.:</strong> <?= esc($tutor['dni']); ?></div>
                    <div><strong>Teléfono:</strong> <?= esc($tutor['telefono']); ?></div>
                    <div><strong>Email:</strong> <?= esc($tutor['email'] ?: '-'); ?></div>
                    <div><strong>Parentesco:</strong> <?= esc($tutor['parentesco'] ?: '-'); ?></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<div class="card mb-4">
    <div class="card-header">Planes asignados</div>
    <div class="card-body">
        <?php if ($planes) { ?>
            <?php foreach ($planes as $plan) { ?>
                <span class="badge bg-info text-dark me-1"><?= esc($plan['nombre']); ?></span>
            <?php } ?>
        <?php } else { ?>
            <span>El socio no tiene planes asignados.</span>
        <?php } ?>
    </div>
</div>

<div class="card">
    <div class="card-header">Liquidaciones y pagos</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Liquidación</th>
                    <th>Monto</th>
                    <th>Pagado</th>
                    <th>Saldo pendiente</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($liquidaciones) { ?>
                    <?php foreach ($liquidaciones as $item) { ?>
                        <tr>
                            <td><?= esc($item['liquidacion']['id']); ?></td>
                            <td><?= esc($item['liquidacion']['nombre']); ?></td>
                            <td>$<?= number_format((float) $item['liquidacion']['monto'], 2, ',', '.'); ?></td>
                            <td>$<?= number_format((float) $item['total_pagado'], 2, ',', '.'); ?></td>
                            <td>$<?= number_format((float) $item['saldo_pendiente'], 2, ',', '.'); ?></td>
                            <td><?= !empty($item['liquidacion']['fecha_vencimiento']) ? esc(date('d/m/Y', strtotime($item['liquidacion']['fecha_vencimiento']))) : '-'; ?></td>
                            <td><?= esc($item['estado']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay liquidaciones para mostrar.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
