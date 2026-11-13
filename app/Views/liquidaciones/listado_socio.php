<?php #echo $titulo; 
?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Información del socio</h5>
        <div class="row">
            <div class="col-md-6">
                <strong>Nombre:</strong>
                <?= esc(trim(($socio['nombre'] ?? '') . ' ' . ($socio['apellido'] ?? ''))); ?>
            </div>
            <div class="col-md-6">
                <strong>D.N.I.:</strong> <?= esc($socio['dni'] ?? '-'); ?>
            </div>
            <div class="col-md-6">
                <strong>Teléfono:</strong> <?= esc($socio['telefono'] ?? '-'); ?>
            </div>
            <div class="col-md-6">
                <strong>Email:</strong> <?= esc($socio['email'] ?? '-'); ?>
            </div>
            <div class="col-12">
                <strong>Domicilio:</strong> <?= esc($socio['domicilio'] ?? '-'); ?>
            </div>
        </div>
    </div>
</div>

<br><br>
<!--<a class="btn btn-primary" href="<?= base_url(); ?>public/liquidaciones/nuevo">+ Nueva liquidación</a>
<a class="btn btn-secondary" href="<?= base_url(); ?>public/socios">Volver a socios</a>-->
<br><br>
<?php if (session()->getFlashdata('error')) { ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')); ?></div>
<?php } ?>

<table class="table table-hover" id="contenido-lista">
    <thead>
        <tr>
            <th>#</th>
            <th>Liquidación</th>
            <th>Monto</th>
            <th>Fecha de Vencimiento</th>
            <th>Estado</th>
            <th>Último cobrador</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($historial)) {
            foreach ($historial as $h) {
                echo '<tr>';
                echo '<td>' . $h['liquidacion']['id'] . '</td>';
                echo '<td>' . $h['liquidacion']['nombre'] . '</td>';
                echo '<td>$' . ($h['total_pagado'] ?? 0) . ' / $' . $h['liquidacion']['monto'] . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($h['liquidacion']['fecha_vencimiento'])) . '</td>';
                echo '<td>' . $h['estado'] . '</td>';
                $cobrador = $h['pago'] && !empty($h['pago']['id_recaudador'])
                    ? trim(($h['pago']['recaudador_apellido'] ?? '') . ', ' . ($h['pago']['recaudador_nombre'] ?? ''))
                    : 'No registrado';
                echo '<td>' . esc($cobrador) . '</td>';
                echo '<td>';
                if ($h['estado'] !== 'Pagado' && $puedePagar) {
                    echo '<a class="btn btn-success" href="' . base_url('public/pagos/nuevo/' . $idSocio . '/' . $h['liquidacion']['id']) . '">Pagar</a>';
                } elseif ($h['estado'] === 'Pagado') {
                    echo '<button class="btn btn-secondary" disabled>Pago realizado</button>';
                } else {
                    echo '<span class="text-muted">Sin permiso de pago</span>';
                }
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>