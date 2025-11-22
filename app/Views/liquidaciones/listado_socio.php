<?php #echo $titulo; 
?>

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
        <?php
        if (!empty($historial)) {
            foreach ($historial as $h) {
                echo '<tr>';
                echo '<td>' . $h['liquidacion']['id'] . '</td>';
                echo '<td>' . $h['liquidacion']['nombre'] . '</td>';
                echo '<td>$' . ($h['pago']['monto'] ?? $h['liquidacion']['monto']) . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($h['liquidacion']['fecha_vencimiento'])) . '</td>';
                echo '<td>' . $h['estado'] . '</td>';
                echo '<td>';
                if ($h['estado'] === 'Pendiente') {
                    echo '<a class="btn btn-success" href="' . base_url('public/pagos/nuevo/' . $idSocio . '/' . $h['liquidacion']['id']) . '">Pagar</a>';
                } else {
                    echo '<button class="btn btn-secondary" disabled>Pago realizado</button>';
                }
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>