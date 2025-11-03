<?php
#echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/socios/nuevo">+ Nuevo socio</a>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
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
    echo '<td>' . $socio['fecha_nac'] . '</td>';
    echo '<td>' . $socio['dni'] . '</td>';
    echo '<td>' . $socio['email'] . '</td>';
    echo '<td>' . $socio['id_zona'] . '</td>';
    
        echo '<td>
        <a class ="btn btn-warning"
         href="' . base_url() . 'public/socios/editar/' . $socio['id'] . '">Editar
         </a>
        <a class ="btn btn-danger"
         href="' . base_url() . 'public/socios/borrar/' . $socio['id'] . '">Borrar
         </a>

        </td>';

    echo '</tr>';
}
?>

    </tbody>
</table>