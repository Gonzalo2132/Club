<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>
<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('mensaje')) ?></div>
<?php endif ?>

<p>Las disciplinas son deportivas. Se mantienen separadas de los planes de suscripción y de las liquidaciones.</p>
<?php if (can_action('disciplinas', 'crear')): ?>
    <a class="btn btn-primary mb-3" href="<?= base_url('public/disciplinas/nuevo') ?>">+ Nueva disciplina</a>
<?php endif ?>

<table class="table table-hover" id="contenido-lista">
    <thead>
        <tr>
            <th>#</th>
            <th>Disciplina</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($disciplinas as $disciplina): ?>
            <tr>
                <td><?= (int) $disciplina['id'] ?></td>
                <td><?= esc($disciplina['nombre']) ?></td>
                <td><?= esc($disciplina['descripcion'] ?? '') ?></td>
                <td>
                    <?php if (can_action('disciplinas', 'ver')): ?>
                        <a class="btn btn-info btn-sm" href="<?= base_url('public/disciplinas/jugadores/' . $disciplina['id']) ?>">Inscriptos</a>
                    <?php endif ?>
                    <?php if (can_action('disciplinas', 'editar')): ?>
                        <a class="btn btn-warning btn-sm" href="<?= base_url('public/disciplinas/editar/' . $disciplina['id']) ?>">Editar</a>
                    <?php endif ?>
                    <?php if (can_action('disciplinas', 'eliminar')): ?>
                        <form class="d-inline" method="post" action="<?= base_url('public/disciplinas/borrar/' . $disciplina['id']) ?>"
                            onsubmit="return confirm('¿Borrar esta disciplina?')">
                            <button class="btn btn-danger btn-sm" type="submit">Borrar</button>
                        </form>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
