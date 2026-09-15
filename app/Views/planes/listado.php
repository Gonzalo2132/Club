<br><br>
<a class="btn btn-primary" href="<?php echo base_url(); ?>public/planes/nuevo">+ Nuevo plan</a>
<br><br>
<table class="table table-hover" id="contenido-lista">
    <thead>
        <th>ID</th>
        <th>Nombre del Plan</th>
        <th>Descripción</th>
        <th>Monto</th>
        <th>Acciones</th>
    </thead>
    <tbody>
        <?php foreach ($planes as $plan) { ?>
        <tr>
            <td><?php echo $plan['id']; ?></td>
            <td><?php echo $plan['nombre']; ?></td>
            <td><?php echo $plan['descripcion']; ?></td>
            <td>$<?php echo number_format($plan['monto'], 2, ',', '.'); ?></td>
            <td>
                <a class ="btn btn-warning" href="<?php echo base_url(); ?>public/planes/editar/<?php echo $plan['id']; ?>">Editar</a>
                <a class ="btn btn-danger" href="<?php echo base_url(); ?>public/planes/borrar/<?php echo $plan['id']; ?>">Borrar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>