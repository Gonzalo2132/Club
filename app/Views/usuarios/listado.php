<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>
<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('mensaje')) ?></div>
<?php endif ?>
<?php if (can_action('usuarios', 'crear')): ?>
    <a class="btn btn-primary" href="<?= base_url('public/usuarios/nuevo') ?>">+ Nuevo usuario</a>
<?php endif ?>
<?php if ($esAdministrador): ?>
    <a class="btn btn-outline-primary" href="<?= base_url('public/roles') ?>">Administrar roles y permisos</a>
<?php endif ?>
<p class="text-muted mt-2">Los usuarios nuevos quedan sin permisos hasta que un administrador les asigne un rol.</p>
<br>
<br>
<table class="table table-hover"id="contenido-lista">
    <thead>
        <th>#</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Nombre de Usuario</th>
        <th>Rol</th>
        <th>D.N.I.</th>
        <th>Acciones</th>
     
    </thead>
    
    <tbody>
        <?php
        foreach ($usuarios as $usuario) {
            echo '<tr>';
                    echo '<td>' . $usuario['id'] . '</td>';
                    echo '<td>' . $usuario['nombre'] . '</td>';
                    echo '<td>' . $usuario['apellido'] . '</td>';
                    echo '<td>' . $usuario['username'] . '</td>';
                    echo '<td>' . esc($usuario['rol_nombre'] ?? 'Sin rol') . '</td>';
                    echo '<td>' . $usuario['dni'] . '</td>';
                
                    echo '<td>';
                    if ($esAdministrador && (int) ($usuario['rol_es_administrador'] ?? 0) !== 1) {
                        echo '<form class="d-flex gap-1 mb-1" method="post" action="' . base_url('public/usuarios/asignar-rol/' . $usuario['id']) . '">
                            <select class="form-select form-select-sm" name="rol_id" aria-label="Rol de ' . esc($usuario['username']) . '" required>
                                <option value="" disabled' . (empty($usuario['rol_id']) ? ' selected' : '') . '>Selecciona un rol</option>';
                        foreach ($roles as $rol) {
                            if ((int) $rol['es_administrador'] === 1) {
                                continue;
                            }
                            $selected = (int) ($usuario['rol_id'] ?? 0) === (int) $rol['id'] ? ' selected' : '';
                            echo '<option value="' . (int) $rol['id'] . '"' . $selected . '>' . esc($rol['nombre']) . '</option>';
                        }
                        echo '</select><button class="btn btn-sm btn-outline-success" type="submit">Asignar</button></form>';
                    } elseif ((int) ($usuario['rol_es_administrador'] ?? 0) === 1) {
                        echo '<span class="badge bg-primary">Administrador protegido</span>';
                    }
                    if (can_action('usuarios', 'editar')) {
                        echo '
                                <a class ="btn btn-warning"
                                href="' . base_url() . 'public/usuarios/editar/' . $usuario['id'] . '">Editar
                                </a>
                            ';
                    }
                    if ((int) $usuario['id'] !== $usuarioActualId
                        && (int) ($usuario['rol_es_administrador'] ?? 0) !== 1
                        && can_action('usuarios', 'eliminar')) {
                        echo '<a class ="btn btn-danger"
                                href="' . base_url() . 'public/usuarios/borrar/' . $usuario['id'] . '">Borrar
                                </a>';
                    }
                    echo '</td>';

            echo '</tr>';}?>

    </tbody>
</table>