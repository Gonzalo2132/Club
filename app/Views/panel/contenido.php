<!-- Content Row -->
<div class="row">


<?php if (in_array('usuarios', $modulosPermitidos, true)): ?>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2 text-center"> <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Usuarios</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalUsuarios?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php if (in_array('socios', $modulosPermitidos, true)): ?>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2 text-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Socios</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalSocios?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php if (in_array('recaudadores', $modulosPermitidos, true)): ?>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2 text-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Recaudadores</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalRecaudadores?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php if (in_array('zonas', $modulosPermitidos, true)): ?>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2 text-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Zonas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalZonas?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php if ($resumenFinanciero !== null): ?>
<div class="col-12 mb-4">
    <h4 class="mb-3">Vencimientos y saldos pendientes</h4>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Deuda vencida</div>
                    <div class="h5 mb-1 font-weight-bold text-gray-800">
                        $<?= number_format($resumenFinanciero['saldoVencido'], 2, ',', '.') ?>
                    </div>
                    <div class="text-muted"><?= $resumenFinanciero['cantidadVencida'] ?> liquidaciones</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Vence en los próximos 7 días</div>
                    <div class="h5 mb-1 font-weight-bold text-gray-800">
                        $<?= number_format($resumenFinanciero['saldoProximo'], 2, ',', '.') ?>
                    </div>
                    <div class="text-muted"><?= $resumenFinanciero['cantidadProxima'] ?> liquidaciones</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo pendiente total</div>
                    <div class="h5 mb-1 font-weight-bold text-gray-800">
                        $<?= number_format($resumenFinanciero['saldoTotal'], 2, ',', '.') ?>
                    </div>
                    <div class="text-muted"><?= $resumenFinanciero['cantidadPendiente'] ?> liquidaciones</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liquidaciones pendientes más próximas</h6>
        </div>
        <div class="card-body table-responsive">
            <?php if ($resumenFinanciero['liquidaciones'] === []): ?>
                <p class="mb-0 text-muted">No hay saldos pendientes para los socios activos.</p>
            <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Socio</th>
                            <th>Liquidación</th>
                            <th>Vencimiento</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumenFinanciero['liquidaciones'] as $liquidacion): ?>
                            <?php $vencida = $liquidacion['fecha_vencimiento'] < date('Y-m-d'); ?>
                            <tr>
                                <td><?= esc($liquidacion['socio_apellido'] . ', ' . $liquidacion['socio_nombre']) ?></td>
                                <td><?= esc($liquidacion['liquidacion_nombre']) ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($liquidacion['fecha_vencimiento']))) ?></td>
                                <td>$<?= number_format((float) $liquidacion['saldo_pendiente'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $vencida ? 'bg-danger' : 'bg-warning text-dark' ?>">
                                        <?= $vencida ? 'Vencida' : 'Pendiente' ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="<?= base_url('public/liquidaciones/listado_socio/' . (int) $liquidacion['id_socio']) ?>">
                                        Ver cuenta
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <?php if ($resumenFinanciero['cantidadPendiente'] > count($resumenFinanciero['liquidaciones'])): ?>
                    <p class="text-muted mt-3 mb-0">Se muestran las primeras 10 liquidaciones pendientes.</p>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Content Row -->
<div class="row">

<!-- Content Column -->
<div class="col-lg-6 mb-4">






</div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
