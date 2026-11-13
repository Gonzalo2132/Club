<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Cajas y movimientos</h3>
        <div class="d-flex gap-2">
            <?php if (can_action('cajas', 'ver')): ?>
                <a class="btn btn-outline-primary" href="<?= base_url('public/cajas/gastos') ?>">
                    <i class="bi bi-receipt"></i> Ver gastos
                </a>
            <?php endif ?>
            <?php if (can_action('cajas', 'crear')): ?>
                <a class="btn btn-success" href="<?= base_url('public/cajas/nuevo-gasto') ?>">
                    <i class="bi bi-dash-circle"></i> Registrar gasto
                </a>
            <a class="btn btn-primary" href="<?= base_url(); ?>public/cajas/nuevo">+ Nueva caja</a>
            <?php endif ?>
        </div>
    </div>

    <div class="alert alert-info">
        Total de gastos no anulados de este mes:
        <strong>$<?= number_format((float) ($gastoMesActual ?? 0), 2, ',', '.') ?></strong>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach ($cajas as $index => $caja) { ?>
            <div class="col-md-2">
                <!-- Pintamos la tarjeta distinto si es la Caja Central (Asumiendo que es el ID 1) -->
                <div class="card h-100 shadow-sm <?= $caja['id'] == 1 ? 'border-dark bg-light' : 'border-primary' ?>">
                    <div class="card-body">
                        <small class="text-muted"><?= $caja['id'] == 1 ? 'Caja Principal' : 'Caja ' . ($index + 1) ?></small>
                        <h5 class="card-title mt-2"><?= esc($caja['denominacion']) ?></h5>
                        <p class="mb-1"><strong>Código:</strong> <?= esc($caja['codigointerno']) ?></p>
                        <p class="mb-1"><strong>Saldo:</strong> $<?= number_format((float) ($caja['saldo'] ?? 0), 2, ',', '.') ?></p>
                        <p class="mb-0"><strong>Descripción:</strong> <?= esc($caja['descripcion']) ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <?php if (can_action('cajas', 'editar')): ?>
                        <a class="btn btn-sm btn-warning me-1" href="<?= base_url() ?>public/cajas/editar/<?= $caja['id'] ?>" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php endif ?>
                        <?php if (can_action('cajas', 'eliminar')): ?>
                        <a class="btn btn-sm btn-danger me-1" href="<?= base_url() ?>public/cajas/borrar/<?= $caja['id'] ?>" title="Borrar" onclick="return confirm('¿Seguro que deseas borrar esta caja?');">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif ?>
                        <?php if (can_action('cajas', 'ver')): ?>
                        <a class="btn btn-sm btn-info" href="<?= base_url() ?>public/cajas/movimientos/<?= $caja['id'] ?>" title="Ver movimientos">
                            <i class="bi bi-list-ul"></i>
                        </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if (can_action('cajas', 'crear')): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Registrar movimiento entre cajas</div>
        <div class="card-body">
            <form method="post" action="<?= base_url() ?>public/cajas/guardar-movimiento">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Caja origen</label>
                        <select name="caja_origen_id" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <!-- El bucle ahora imprime la Caja Central automáticamente -->
                            <?php foreach ($cajas as $caja) : ?>
                                <option value="<?= $caja['id'] ?>"><?= esc($caja['denominacion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Caja destino</label>
                        <select name="caja_destino_id" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <!-- El bucle ahora imprime la Caja Central automáticamente -->
                            <?php foreach ($cajas as $caja) : ?>
                                <option value="<?= $caja['id'] ?>"><?= esc($caja['denominacion']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Monto</label>
                        <input type="number" name="monto" class="form-control" min="1" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" name="concepto" class="form-control" placeholder="Ej: Cobranza, entrega, recaudación">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif ?>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Todos los movimientos</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Concepto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movimientos)) : ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay movimientos registrados todavía.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($movimientos as $movimiento) : ?>
                            <tr>
                                <td><?= esc($movimiento['origen'] ?? 'Caja Central') ?></td>
                                <td><?= esc($movimiento['destino'] ?? 'Caja Central') ?></td>
                                <td><?= esc(strtoupper($movimiento['tipo'] ?? 'TRANSFERENCIA')) ?></td>
                                <td>$<?= number_format((float) ($movimiento['monto'] ?? 0), 2, ',', '.') ?></td>
                                <td><?= esc($movimiento['concepto'] ?? 'Movimiento de caja') ?></td>
                                <td><?= esc(date('d/m/Y H:i', strtotime($movimiento['fecha'] ?? 'now'))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>