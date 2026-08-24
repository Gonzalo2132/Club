<?php
#echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/recaudadores/nuevo">+ Nuevo recaudador</a>
<br>
<br>
<table class="table table-hover" id="contenido-lista">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>D.N.I.</th>
            <th>Teléfono</th>
            <th>Caja</th>
            <th>Zona</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($recaudadores as $recaudador) {
            $cajaNombre = '';
            foreach ($cajas as $caja) {
                if ($caja['id'] == $recaudador['id_caja']) {
                    $cajaNombre = $caja['denominacion'];
                    break;
                }
            }

            $zonaNombre = '';
            foreach ($zonas as $zona) {
                if ($zona['id'] == ($recaudador['id_zona'] ?? null)) {
                    $zonaNombre = $zona['denominacion'];
                    break;
                }
            }

            echo '<tr>';
            echo '<td>' . $recaudador['id'] . '</td>';
            echo '<td>' . $recaudador['nombre'] . '</td>';
            echo '<td>' . $recaudador['apellido'] . '</td>';
            echo '<td>' . $recaudador['dni'] . '</td>';
            echo '<td>' . $recaudador['telefono'] . '</td>';
            echo '<td>' . $cajaNombre . '</td>';
            echo '<td>' . ($zonaNombre ?: 'Sin zona') . '</td>';
            echo '<td>
                <a class="btn btn-warning" href="' . base_url() . 'public/recaudadores/editar/' . $recaudador['id'] . '">Editar</a>
                <a class="btn btn-danger" href="' . base_url() . 'public/recaudadores/borrar/' . $recaudador['id'] . '">Borrar</a>
            </td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>