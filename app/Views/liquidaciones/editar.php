<form action="
<?php 
echo base_url();

?>public/liquidaciones/actualizar/<?php echo $liquidacion[0]['id'];?>
" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" 
    placeholder="" value="<?php echo $liquidacion[0]['nombre'];?>">

    <label class="form-label" for="monto">Monto</label>
    <input class="form-control" type="number" name="monto" id="monto" 
    placeholder="" value="<?php echo $liquidacion[0]['monto'];?>">

    <label class="form-label" for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input class="form-control" type="date" name="fecha_vencimiento" id="fecha_vencimiento" 
    placeholder="dd/mm/AAAA" value="<?php echo $liquidacion[0]['fecha_vencimiento'];?>">

    <label class="form-label" for="id_plan">ID del Plan</label>
    <select class="form-control" name="id_plan" id="id_plan">
        <option value="">-</option>
        <?php foreach ($planes as $plan) { ?>
            <option value="<?= $plan['id'] ?>" <?= ($liquidacion[0]['id_plan'] == $plan['id']) ? 'selected' : '' ?>>
                <?= $plan['id'] ?>
            </option>
        <?php } ?>
    </select>

    <label class="form-label" for="nombre_plan">Nombre del Plan</label>
    <select class="form-control" name="nombre_plan" id="nombre_plan">
        <option value="Sin plan">Sin plan</option>
        <?php foreach ($planes as $plan) { ?>
            <option value="<?= $plan['nombre'] ?>" <?= ($liquidacion[0]['nombre_plan'] == $plan['nombre']) ? 'selected' : '' ?>>
                <?= $plan['nombre'] ?>
            </option>
        <?php } ?>
    </select>
    <br>

    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="<?php echo base_url(); ?>public/liquidaciones">Cancelar</a>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectId = document.getElementById('id_plan');
            const selectNombre = document.getElementById('nombre_plan');


            selectId.addEventListener('change', function() {
                selectNombre.selectedIndex = selectId.selectedIndex;
            });

            selectNombre.addEventListener('change', function() {
                selectId.selectedIndex = selectNombre.selectedIndex;
            });
        });
    </script>