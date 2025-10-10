<?php
echo $titulo;
?>
<br>
<br>
<a class="btn btn-primary" href="<?php 
echo base_url();
?>public/clientes/nuevo">+ Nuevo Cliente</a>
<br>
<br>
<table class="table table-hover"id="id_listado_clientes">
    <thead>
        <th>#</th>
        <th>denominación</th>
        <th>Domicilio</th>
        <th>Email</th>

    </thead>
    <tbody>


<?php
foreach ($clientes as $cliente) {
    echo '<tr>';
    echo '<td>' . $cliente['id'] . '</td>';
    echo '<td>' . $cliente['denominacion'] . '</td>';
    echo '<td>' . $cliente['domicilio'] . '</td>';
    echo '<td>' . $cliente['email'] . '</td>';
        echo '<td>
        <a class ="btn btn-warning"
         href="' . base_url() . 'public/clientes/editar/' . $cliente['id'] . '">Editar
         </a>
        <a class ="btn btn-danger"
         href="' . base_url() . 'public/clientes/borrar/' . $cliente['id'] . '">Borrar
         </a>

        </td>';

    echo '</tr>';
}
?>

    </tbody>
</table>