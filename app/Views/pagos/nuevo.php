<?php
#echo $titulo;
?>
<?php #echo $titulo; ?>
<br><br>
<h3>Pago de liquidación: <?= $liquidacion['nombre'] ?></h3>
<p>Socio: <?= $socio['nombre'].' '.$socio['apellido'] ?></p>
<p>Monto base: <?= $liquidacion['monto'] ?></p>
<p>Fecha de vencimiento: <?= date('d/m/Y', strtotime($liquidacion['fecha_vencimiento'])) ?></p>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>

<br><br>

<!-- Formulario para registrar un nuevo pago -->
<form action="
<?php
echo base_url();

?>public/pagos/guardar
" method="post">
    <input type="hidden" name="id_socio" value="<?= $socio['id'] ?>">
    <input type="hidden" name="id_liquidacion" value="<?= $liquidacion['id'] ?>">

    <label class="form-label" for="monto">Monto</label>
    <input class="form-control" type="number" name="monto" id="monto" value="<?= esc($liquidacion['monto'] ?? '') ?>" min="0" step="0.01" placeholder="Ingrese un monto">

    <label class="form-label" for="fecha_pago">Fecha de Pago</label>
    <input class="form-control" type="date" name="fecha_pago" id="fecha_pago" value="<?= date('Y-m-d') ?>" placeholder="dd/mm/AAAA">

    <label class="form-label" for="id_recaudador">Recaudador que recibió el pago</label>
    <select class="form-select" id="id_recaudador" name="id_recaudador" <?= !empty($recaudadores) ? 'required' : '' ?>>
        <?php if (empty($recaudadores)): ?>
            <option value="">Sin recaudadores activos en la zona</option>
        <?php else: ?>
            <option value="">Selecciona quién recibió el pago</option>
            <?php foreach ($recaudadores as $recaudador): ?>
                <option value="<?= (int) $recaudador['id'] ?>"
                    <?= (string) old('id_recaudador', $recaudadorSeleccionado) === (string) $recaudador['id'] ? 'selected' : '' ?>>
                    <?= esc($recaudador['apellido'] . ', ' . $recaudador['nombre']) ?>
                </option>
            <?php endforeach ?>
        <?php endif ?>
    </select>

    <label class="form-label" for="id_caja">Caja</label>
    <select class="form-control" id="id_caja" name="id_caja">
        <?php
        foreach ($cajas as $c) {
            echo '<option value="' . $c['id'] . '">' . $c['denominacion'] . '</option>';
        }
        ?>
    </select>

    <label class="form-label" for="forma_pago">Forma de Pago</label>
    <select class="form-control" id="forma_pago" name="forma_pago">
        <option value="Efectivo">Efectivo</option>
        <option value="Tarjeta">Tarjeta</option>
        <option value="Transferencia">Transferencia</option>
    </select>

    <br>
    <button type="submit" class="btn btn-success">Registrar pago</button>
</form>