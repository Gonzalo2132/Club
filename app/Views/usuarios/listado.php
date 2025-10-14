<?php
echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/usuarios/nuevo">+ Nuevo usuario</a>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
    <thead>
        <th>#</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Nombre de Usuario</th>
        <th>Clave</th>
        <th>D.N.I.</th>
     
    </thead>
    <tbody>


<?php
foreach ($usuarios as $usuario) {
    echo '<tr>';
    echo '<td>' . $usuario['id'] . '</td>';
    echo '<td>' . $usuario['nombre'] . '</td>';
    echo '<td>' . $usuario['apellido'] . '</td>';
    echo '<td>' . $usuario['username'] . '</td>';
    echo '<td>' . $usuario['clave'] . '</td>';
    echo '<td>' . $usuario['dni'] . '</td>';
    
        echo '<td>
        <a class ="btn btn-warning"
         href="' . base_url() . 'public/usuarios/editar/' . $usuario['id'] . '">Editar
         </a>
        <a class ="btn btn-danger"
         href="' . base_url() . 'public/usuarios/borrar/' . $usuario['id'] . '">Borrar
         </a>

        </td>';

    echo '</tr>';
}
?>

    </tbody>
</table>