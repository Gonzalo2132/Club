<?php
#echo $titulo;
?>
<br>
<br>
<?php if (can_action('socios', 'crear')): ?>
<a class="btn btn-primary" href="<?php
                                    echo base_url();
                                    ?>public/socios/nuevo">+ Nuevo socio</a>
<?php endif ?>
<br>
<br>
<table class="table table-hover" id="contenido-lista">
    <thead>
        <th>#</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Domicilio</th>
        <th>Teléfono</th>
        <th>Fecha de Nacimiento</th>
        <th>D.N.I.</th>
        <th>Email</th>
        <th>Zona</th>
        <th>Planes</th>
        <th>Disciplinas</th>
        <th>Tutor</th>
        <th>Acciones</th>

    </thead>
    <tbody>


        <?php
        foreach ($socios as $socio) {
            echo '<tr>';
            echo '<td>' . $socio['id'] . '</td>';
            echo '<td>' . $socio['nombre'] . '</td>';
            echo '<td>' . $socio['apellido'] . '</td>';
            echo '<td>' . $socio['domicilio'] . '</td>';
            echo '<td>' . $socio['telefono'] . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($socio['fecha_nac'])) . '</td>';
            echo '<td>' . $socio['dni'] . '</td>';
            echo '<td>' . $socio['email'] . '</td>';
            $zonaNombre = '';
            foreach ($zonas as $zona) {
                if ($zona['id'] == $socio['id_zona']) {
                    $zonaNombre = $zona['denominacion'];
                    break;
                }
            }
            echo '<td>' . $zonaNombre . '</td>';
            echo '<td><span class="badge bg-info text-dark">' . esc(!empty($socio['planes_nombres']) ? str_replace(' | ', ', ', $socio['planes_nombres']) : 'Sin plan') . '</span></td>';
            echo '<td><span class="badge bg-success">' . esc(!empty($socio['disciplinas_nombres']) ? str_replace(' | ', ', ', $socio['disciplinas_nombres']) : 'Sin disciplina') . '</span></td>';
            echo '<td>' . esc($socio['tutores_nombres'] ?? '-') . '</td>';

            echo '<td>';
            if (can_action('socios', 'editar')) {
                echo '<a class ="btn btn-warning"
         href="' . base_url() . 'public/socios/editar/' . $socio['id'] . '"
        title="Editar">
        <i class="bi bi-pencil"></i>
         </a>';
            }
            if (can_action('socios', 'eliminar')) {
                echo '<a class ="btn btn-danger"
         href="' . base_url() . 'public/socios/borrar/' . $socio['id'] . '"
        title="Borrar">
        <i class="bi bi-trash"></i>
         </a>';
            }
            if (can_action('socios', 'ver')) {
                echo '<a class="btn btn-secondary"
         href="' . base_url() . 'public/socios/informe/' . $socio['id'] . '"
        title="Vista previa del socio" aria-label="Vista previa del socio">
        <i class="bi bi-eye"></i>
         </a>';
            }
            if (can_action('liquidaciones', 'ver')) {
                echo '<a class="btn btn-info"
         href="' . base_url() . 'public/liquidaciones/listado_socio/' . $socio['id'] . '"
        title="Ver pagos">
        <i class="bi bi-cash-stack"></i>
         </a>';
            }
            echo '</td>';

            echo '</tr>';
        }
        ?>

    </tbody>
</table>