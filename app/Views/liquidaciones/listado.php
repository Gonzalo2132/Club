<?php
#echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/liquidaciones/nuevo">+ Nueva Liquidacion</a>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
    <thead>
        <th>#</th>
        <th>Nombre</th>
        <th>Monto</th>
        <th>ID Plan</th>
        <th>Plan</th>
        <th>Fecha de Vencimiento</th>
        <th>Acciones</th>



        

    </thead>
    <tbody>


<?php
foreach ($liquidaciones as $liquidacion) {
    echo '<tr>';
    echo '<td>' . $liquidacion['id'] . '</td>';
    echo '<td>' . esc($liquidacion['nombre']) . '</td>';
    echo '<td>$' . $liquidacion['monto'] . '</td>';
    echo '<td>' . esc($liquidacion['id_plan'] ?? '-') . '</td>';
    echo '<td>' . esc($liquidacion['nombre_plan'] ?: 'Sin plan') . '</td>';
    echo '<td>' . date('d/m/Y', strtotime($liquidacion['fecha_vencimiento'])) . '</td>';

    
    echo '<td>
                <a class="btn btn-warning" href="' . base_url() . 'public/liquidaciones/editar/' . $liquidacion['id'] . '">Editar</a>
                <a class="btn btn-danger" href="' . base_url() . 'public/liquidaciones/borrar/' . $liquidacion['id'] . '">Borrar</a>
            </td>';
        echo '</tr>';
    }
    ?>

        </tbody>
    </table>