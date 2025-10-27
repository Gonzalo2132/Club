<?php
echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/zonas/nuevo">+ Nueva zona</a>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
    <thead>
        <th>#</th>
        <th>Denominación</th>
        <th>Estado</th>

        

    </thead>
    <tbody>


<?php
foreach ($zonas as $zona) {
    echo '<tr>';
    echo '<td>' . $zona['id'] . '</td>';
    echo '<td>' . $zona['denominacion'] . '</td>';
    echo '<td>' . $zona['activo'] . '</td>';

    
        echo '<td>
        <a class ="btn btn-warning"
         href="' . base_url() . 'public/zonas/editar/' . $zona['id'] . '">Editar
         </a>
        <a class ="btn btn-danger"
         href="' . base_url() . 'public/zonas/borrar/' . $zona['id'] . '">Borrar
         </a>

        </td>';

    echo '</tr>';
}
?>

    </tbody>
</table>