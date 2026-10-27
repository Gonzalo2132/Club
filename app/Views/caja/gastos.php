<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Registro de gastos</h3>
        <div class="d-flex gap-2">
            <a class="btn btn-secondary" href="<?= base_url('public/cajas') ?>">Volver a cajas</a>
            <?php if (can_action('cajas', 'crear')): ?>
                <a class="btn btn-success" href="<?= base_url('public/cajas/nuevo-gasto') ?>">Registrar gasto</a>
            <?php endif ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
            <form method="get" action="<?= base_url('public/cajas/gastos') ?>" class="d-flex gap-2 align-items-center">
                <label for="filtro-caja" class="form-label mb-0">Filtrar por caja</label>
                <select class="form-select" id="filtro-caja" name="caja">
                    <option value="">Todas las cajas</option>
                    <?php foreach ($cajas as $caja): ?>
                        <option value="<?= (int) $caja['id'] ?>" <?= (string) ($cajaSeleccionada ?? '') === (string) $caja['id'] ? 'selected' : '' ?>>
                            <?= esc($caja['denominacion']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <button class="btn btn-outline-primary" type="submit">Filtrar</button>
            </form>
            <div class="alert alert-info mb-0">
                Gastos no anulados de este mes: <strong>$<?= number_format((float) $totalMes, 2, ',', '.') ?></strong>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            Gastos registrados — <?= (int) $totalRegistros ?> en total, página <?= (int) $pagina ?> de <?= (int) $totalPaginas ?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Caja</th>
                        <th>Categoría / detalle</th>
                        <th>Destinatario</th>
                        <th>Monto</th>
                        <th>Registrado por</th>
                        <th>Comprobante</th>
                        <th>Estado / acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gastos)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Todavía no hay gastos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($gastos as $gasto): ?>
                            <tr>
                                <td><?= esc(date('d/m/Y', strtotime($gasto['fecha_gasto']))) ?></td>
                                <td><?= esc($gasto['caja_nombre']) ?></td>
                                <td>
                                    <strong><?= esc($categorias[$gasto['categoria']] ?? $gasto['categoria']) ?></strong>
                                    <div><?= esc($gasto['concepto']) ?></div>
                                </td>
                                <td><?= esc($gasto['destinatario'] ?: '-') ?></td>
                                <td>$<?= number_format((float) $gasto['monto'], 2, ',', '.') ?></td>
                                <td>
                                    <?= esc($gasto['creador_nombre'] ?? 'Usuario no disponible') ?>
                                    <div class="small text-muted"><?= esc(date('d/m/Y H:i', strtotime($gasto['fecha_alta']))) ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($gasto['comprobante'])): ?>
                                        <a href="<?= base_url('public/cajas/gastos/comprobante/' . (int) $gasto['id']) ?>">Descargar</a>
                                    <?php else: ?>
                                        <span class="text-muted">Sin comprobante</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if ($gasto['fecha_anulacion'] === null): ?>
                                        <span class="badge bg-success">Vigente</span>
                                        <?php if (can_action('cajas', 'editar')): ?>
                                            <form method="post" class="mt-2" action="<?= base_url('public/cajas/anular-gasto/' . (int) $gasto['id']) ?>"
                                                onsubmit="return confirm('¿Anular este gasto? Se conservará en el historial.');">
                                                <input class="form-control form-control-sm mb-1" type="text" name="motivo_anulacion"
                                                    maxlength="255" placeholder="Motivo obligatorio" required>
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Anular</button>
                                            </form>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Anulado</span>
                                        <div class="small">
                                            <?= esc($gasto['anulador_nombre'] ?? 'Usuario no disponible') ?>
                                            <?= esc(date('d/m/Y H:i', strtotime($gasto['fecha_anulacion']))) ?>:
                                            <?= esc($gasto['motivo_anulacion'] ?? '') ?>
                                        </div>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPaginas > 1): ?>
        <?php
            $parametrosPaginacion = [];
            if ($cajaSeleccionada !== null) {
                $parametrosPaginacion['caja'] = $cajaSeleccionada;
            }
        ?>
        <nav class="mt-3" aria-label="Paginación de gastos">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= base_url('public/cajas/gastos') . '?' . http_build_query($parametrosPaginacion + ['pagina' => max(1, $pagina - 1)]) ?>">Anterior</a>
                </li>
                <li class="page-item disabled"><span class="page-link"><?= (int) $pagina ?> / <?= (int) $totalPaginas ?></span></li>
                <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= base_url('public/cajas/gastos') . '?' . http_build_query($parametrosPaginacion + ['pagina' => min($totalPaginas, $pagina + 1)]) ?>">Siguiente</a>
                </li>
            </ul>
        </nav>
    <?php endif ?>
</div>
