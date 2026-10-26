<br><br>
<?php if (can_action('planes', 'crear')): ?>
<a class="btn btn-primary" href="<?php echo base_url(); ?>public/planes/nuevo">+ Nuevo plan</a>
<?php endif ?>
<br><br>
<table class="table table-hover" id="contenido-lista">
    <thead>
        <th>ID</th>
        <th>Nombre del Plan</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </thead>
    <tbody>
        <?php foreach ($planes as $plan) { ?>
        <tr>
            <td><?php echo $plan['id']; ?></td>
            <td><?php echo $plan['nombre']; ?></td>
            <td><?php echo $plan['descripcion']; ?></td>
            <td>
                <?php if (can_action('planes', 'editar')): ?>
                <a class ="btn btn-warning" href="<?php echo base_url(); ?>public/planes/editar/<?php echo $plan['id']; ?>">Editar</a>
                <?php endif ?>
                <?php if (can_action('planes', 'eliminar')): ?>
                <a class ="btn btn-danger" href="<?php echo base_url(); ?>public/planes/borrar/<?php echo $plan['id']; ?>">Borrar</a>
                <?php endif ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>