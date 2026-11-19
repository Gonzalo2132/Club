<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Editar movimiento #<?= (int) $movimiento['id'] ?></h3>
        <a class="btn btn-secondary" href="<?= base_url('public/cajas/movimientos') ?>">Volver</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= base_url('public/cajas/actualizar-movimiento/' . (int) $movimiento['id']) ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label" for="caja_origen_id">Caja origen</label>
                        <select name="caja_origen_id" id="caja_origen_id" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <?php $origenActual = old('caja_origen_id', $movimiento['caja_origen_id'] ?? ''); ?>
                            <?php foreach ($cajas as $caja) : ?>
                                <option value="<?= (int) $caja['id'] ?>" <?= (string) $origenActual === (string) $caja['id'] ? 'selected' : '' ?>>
                                    <?= esc($caja['denominacion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="caja_destino_id">Caja destino</label>
                        <select name="caja_destino_id" id="caja_destino_id" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <?php $destinoActual = old('caja_destino_id', $movimiento['caja_destino_id'] ?? ''); ?>
                            <?php foreach ($cajas as $caja) : ?>
                                <option value="<?= (int) $caja['id'] ?>" <?= (string) $destinoActual === (string) $caja['id'] ? 'selected' : '' ?>>
                                    <?= esc($caja['denominacion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="monto">Monto</label>
                        <input type="number" name="monto" id="monto" class="form-control" min="0.01" step="0.01"
                            value="<?= esc(old('monto', $movimiento['monto'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="concepto">Concepto</label>
                        <input type="text" name="concepto" id="concepto" class="form-control" maxlength="255"
                            value="<?= esc(old('concepto', $movimiento['detalle_concepto'] ?? '')) ?>">
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="<?= base_url('public/cajas/movimientos') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>