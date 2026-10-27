<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Registrar gasto</h3>
        <a class="btn btn-secondary" href="<?= base_url('public/cajas/gastos') ?>">Volver a gastos</a>
    </div>

    <?php if (empty($cajas)): ?>
        <div class="alert alert-warning">No hay cajas activas para registrar un gasto.</div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= base_url('public/cajas/guardar-gasto') ?>" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="id_caja">Caja de donde salió el dinero</label>
                            <select class="form-select" name="id_caja" id="id_caja" required>
                                <option value="">Seleccionar caja</option>
                                <?php foreach ($cajas as $caja): ?>
                                    <option value="<?= (int) $caja['id'] ?>"
                                        <?= (string) old('id_caja', $cajaSeleccionada ?? '') === (string) $caja['id'] ? 'selected' : '' ?>>
                                        <?= esc($caja['denominacion']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="categoria">Categoría</label>
                            <select class="form-select" name="categoria" id="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $valor => $etiqueta): ?>
                                    <option value="<?= esc($valor) ?>" <?= old('categoria') === $valor ? 'selected' : '' ?>>
                                        <?= esc($etiqueta) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="concepto">Concepto o detalle</label>
                            <input class="form-control" type="text" name="concepto" id="concepto" maxlength="255"
                                placeholder="Ej.: Pago de luz de octubre" value="<?= esc(old('concepto')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="destinatario">Proveedor o destinatario (opcional)</label>
                            <input class="form-control" type="text" name="destinatario" id="destinatario" maxlength="120"
                                value="<?= esc(old('destinatario')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="monto">Monto</label>
                            <input class="form-control" type="number" name="monto" id="monto" min="0.01" step="0.01"
                                value="<?= esc(old('monto')) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="fecha_gasto">Fecha del gasto</label>
                            <input class="form-control" type="date" name="fecha_gasto" id="fecha_gasto"
                                max="<?= date('Y-m-d') ?>" value="<?= esc(old('fecha_gasto', date('Y-m-d'))) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="comprobante">Comprobante (opcional)</label>
                            <input class="form-control" type="file" name="comprobante" id="comprobante"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/jpeg,image/png,image/webp">
                            <div class="form-text">PDF, JPG, PNG o WEBP; máximo 5 MB.</div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success" type="submit">Guardar gasto</button>
                        <a class="btn btn-outline-secondary" href="<?= base_url('public/cajas/gastos') ?>">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif ?>
</div>
