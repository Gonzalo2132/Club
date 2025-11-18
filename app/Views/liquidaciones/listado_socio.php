<?php #echo $titulo; ?>

<br><br>
<!--<a class="btn btn-primary" href="<?= base_url(); ?>public/liquidaciones/nuevo">+ Nueva liquidación</a>
<a class="btn btn-secondary" href="<?= base_url(); ?>public/socios">Volver a socios</a>-->
<br><br>

<table class="table table-hover" id="contenido-lista">
    <thead>
        <tr>
            <th>#</th>
            <th>Liquidación</th>
            <th>Monto</th>
            <th>Fecha de Vencimiento</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($historial)): ?>
            <?php foreach ($historial as $h): ?>
                <tr>
                    <td><?= $h['liquidacion']['id'] ?></td>
                    <td><?= $h['liquidacion']['nombre'] ?></td>
                    <td><?= $h['pago']['monto'] ?? $h['liquidacion']['monto'] ?></td>
                    <td><?= $h['liquidacion']['fecha_vencimiento'] ?></td>
                    <td><?= $h['estado'] ?></td>
                    <td>
                        <?php if ($h['estado'] === 'Pendiente'): ?>
                            <a class="btn btn-success" href="<?= base_url('public/pagos/nuevo/'.$idSocio.'/'.$h['liquidacion']['id']) ?>">Pagar</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Pago realizado</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No hay liquidaciones registradas para este socio</td></tr>
        <?php endif; ?>
    </tbody>
</table>
