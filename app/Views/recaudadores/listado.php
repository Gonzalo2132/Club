<?php
echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/recaudadores/nuevo">+ Nuevo recaudador</a>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
    <thead>
        <th>#</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>D.N.I.</th>
     
    </thead>
    <tbody>


<?php
foreach (recaudadores as $recaudador) {
    echo '<tr>';
    echo '<td>' . $recaudador['id'] . '</td>';
    echo '<td>' . $recaudador['nombre'] . '</td>';
    echo '<td>' . $recaudador['apellido'] . '</td>';
    echo '<td>' . $recaudador['dni'] . '</td>';
    
        echo '<td>
        <a class ="btn btn-warning"
         href="' . base_url() . 'public/recaudadores/editar/' . $recaudador['id'] . '">Editar
         </a>
        <a class ="btn btn-danger"
         href="' . base_url() . 'public/recaudador/borrar/' . $recaudador['id'] . '">Borrar
         </a>

        </td>';

    echo '</tr>';
}
?>

    </tbody>
</table>