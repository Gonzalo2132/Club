<?php
$codigoSugerido = old('codigointerno');
if (empty($codigoSugerido)) {
    $codigoSugerido = 'REC-' . strtoupper(bin2hex(random_bytes(4)));
}
?>

<form action="
<?php
echo base_url();
?>public/cajas/guardar
" method="post">

    <label class="form-label" for="denominacion">Denominación</label>
    <input class="form-control" type="text" name="denominacion" id="denominacion" placeholder="" value="<?= esc(old('denominacion')) ?>" required>

    <label class="form-label" for="descripcion">Descripcion</label>
    <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="" value="<?= esc(old('descripcion')) ?>">

    <label class="form-label" for="codigointerno">Codigo Interno</label>
    <input class="form-control" type="text" name="codigointerno" id="codigointerno" placeholder="" value="<?= esc($codigoSugerido) ?>">

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="
<?php
echo base_url();
?>public/cajas
">Cancelar</a>

</form>