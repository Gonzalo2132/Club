<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Caja y movimientos</h3>
        <a class="btn btn-primary" href="<?php echo base_url(); ?>public/cajas/nuevo">+ Nueva caja</a>
    </div>

    <div class="row g-3 mb-4">
        <?php
        $cajasVisibles = array_slice($cajas, 0, 5);
        foreach ($cajasVisibles as $index => $caja) {
            $numeroCaja = $index + 1;
            ?>
            <div class="col-md-2">
                <div class="card h-100 border-primary shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Caja <?= $numeroCaja ?></small>
                        <h5 class="card-title mt-2"><?= esc($caja['denominacion']) ?></h5>
                        <p class="mb-1"><strong>Código:</strong> <?= esc($caja['codigointerno']) ?></p>
                        <p class="mb-0"><strong>Descripción:</strong> <?= esc($caja['descripcion']) ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a class="btn btn-sm btn-warning me-2" href="<?= base_url() ?>public/cajas/editar/<?= $caja['id'] ?>">Editar</a>
                        <a class="btn btn-sm btn-danger" href="<?= base_url() ?>public/cajas/borrar/<?= $caja['id'] ?>">Borrar</a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="col-md-2">
            <div class="card h-100 border-dark bg-light shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Caja central</small>
                    <h5 class="card-title mt-2"><?= esc($cajaCentral['denominacion']) ?></h5>
                    <p class="mb-1"><strong>Saldo:</strong> $<?= number_format((float) ($cajaCentral['saldo_actual'] ?? 0), 2, ',', '.') ?></p>
                    <p class="mb-0"><?= esc($cajaCentral['descripcion']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Registrar movimiento entre cajas</div>
        <div class="card-body">
            <form method="post" action="<?= base_url() ?>public/cajas/guardar-movimiento">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Caja origen</label>
                        <select name="caja_origen_id" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($cajas as $caja) : ?>
                                <option value="<?= $caja['id'] ?>"><?= esc($caja['denominacion']) ?></option>
                            <?php endforeach; ?>
                            <option value="0">Caja Central</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Caja destino</label>
                        <select name="caja_destino_id" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($cajas as $caja) : ?>
                                <option value="<?= $caja['id'] ?>"><?= esc($caja['denominacion']) ?></option>
                            <?php endforeach; ?>
                            <option value="0">Caja Central</option>
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

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Movimientos recientes</div>
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
