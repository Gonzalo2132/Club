<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>
<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('mensaje')) ?></div>
<?php endif ?>

<p>Los permisos se asignan por módulo. La cuenta administradora puede gestionar roles y asignarlos a los usuarios.</p>
<a class="btn btn-primary mb-3" href="<?= base_url('public/roles/nuevo') ?>">Crear rol</a>

<table class="table table-hover" id="contenido-lista">
    <thead>
        <tr>
            <th>Rol</th>
            <th>Módulos habilitados</th>
            <th>Usuarios</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= esc($role['nombre']) ?><?= (int) $role['es_administrador'] === 1 ? ' (protegido)' : '' ?></td>
                <td>
                    <?php
                    $labels = [];
                    foreach ($role['modulos'] as $permission) {
                        $labels[] = \App\Libraries\AccessControl::modules()[$permission['modulo']] ?? $permission['modulo'];
                    }
                    echo esc(implode(', ', $labels));
                    ?>
                </td>
                <td><?= (int) $role['usuarios_asignados'] ?></td>
                <td>
                    <?php if ((int) $role['es_administrador'] !== 1): ?>
                        <a class="btn btn-warning btn-sm" href="<?= base_url('public/roles/editar/' . $role['id']) ?>">Editar</a>
                        <?php if ((int) $role['usuarios_asignados'] === 0): ?>
                            <form class="d-inline" method="post" action="<?= base_url('public/roles/borrar/' . $role['id']) ?>"
                                onsubmit="return confirm('¿Borrar este rol?')">
                                <button class="btn btn-danger btn-sm" type="submit">Borrar</button>
                            </form>
                        <?php endif ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
