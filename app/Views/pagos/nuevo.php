<?php
#echo $titulo;
?>
<?php #echo $titulo; ?>
<br><br>
<h3>Pago de liquidación: <?= $liquidacion['nombre'] ?></h3>
<p>Socio: <?= $socio['nombre'].' '.$socio['apellido'] ?></p>
<p>Monto base: <?= $liquidacion['monto'] ?></p>
<p>Fecha de vencimiento: <?= $liquidacion['fecha_vencimiento'] ?></p>

<br><br>

<!-- Formulario para registrar un nuevo pago -->
<form method="post" action="<?= base_url('public/pagos/guardar') ?>">
    <input type="hidden" name="id_socio" value="<?= $socio['id'] ?>">
    <input type="hidden" name="id_liquidacion" value="<?= $liquidacion['id'] ?>">

    <div class="form-group">
        <label>Monto</label>
        <input type="text" name="monto" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Fecha de pago</label>
        <input type="date" name="fecha_pago" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Forma de Pago</label>
        <select name="id_caja" class="form-control" required>
            <?php foreach ($cajas as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['denominacion'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>
    <button type="submit" class="btn btn-success">Registrar pago</button>
</form>
