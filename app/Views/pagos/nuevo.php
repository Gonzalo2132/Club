<?php
#echo $titulo;
?>
<?php #echo $titulo; ?>
<br><br>
<h3>Pago de liquidación: <?= $liquidacion['nombre'] ?></h3>
<p>Socio: <?= $socio['nombre'].' '.$socio['apellido'] ?></p>
<p>Monto base: <?= $liquidacion['monto'] ?></p>
<p>Fecha de vencimiento: <?= date('d/m/Y', strtotime($liquidacion['fecha_vencimiento'])) ?></p>

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
    <input class="form-control" type="text" name="monto" id="monto" placeholder="Ingrese un monto">

    <label class="form-label" for="fecha_pago">Fecha de Pago</label>
    <input class="form-control" type="date" name="fecha_pago" id="fecha_pago" placeholder="dd/mm/AAAA">

    <label class="form-label" for="id_caja">Forma de Pago</label>
    <select class="form-control" id="id_caja" name="id_caja">
        <?php
        foreach ($cajas as $c) {
            echo '<option value="' . $c['id'] . '">' . $c['denominacion'] . '</option>';
        }
        ?>
    </select>

    <br>
    <button type="submit" class="btn btn-success">Registrar pago</button>
</form>
