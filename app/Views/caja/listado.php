<?php
echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/cajas/nuevo">+ Nueva caja</a>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
    <thead>
        <th>#</th>
        <th>denominación</th>
        <th>codigo interno</th>
        

    </thead>
    <tbody>


<?php
foreach ($cajas as $caja) {
    echo '<tr>';
    echo '<td>' . $caja['id'] . '</td>';
    echo '<td>' . $caja['denominacion'] . '</td>';
    echo '<td>' . $caja['codigointerno'] . '</td>';
    
        echo '<td>
        <a class ="btn btn-warning"
         href="' . base_url() . 'public/cajas/editar/' . $caja['id'] . '">Editar
         </a>
        <a class ="btn btn-danger"
         href="' . base_url() . 'public/cajas/borrar/' . $caja['id'] . '">Borrar
         </a>

        </td>';

    echo '</tr>';
}
?>

    </tbody>
</table>