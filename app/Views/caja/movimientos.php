<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Movimientos - <?= esc($cajaNombre ?? 'Todas las cajas') ?></h3>
        <a class="btn btn-secondary" href="<?= base_url() ?>public/cajas">Volver</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Listado de movimientos</div>
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
                            <td colspan="6" class="text-center text-muted py-4">No hay movimientos registrados.</td>
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
