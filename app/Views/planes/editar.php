<form action="<?php echo base_url(); ?>public/planes/actualizar/<?php echo $plan[0]['id'];?>" method="post">

    <label class="form-label" for="nombre">Nombre</label>
    <input class="form-control" type="text" name="nombre" id="nombre" value="<?php echo $plan[0]['nombre'];?>" required>

    <label class="form-label" for="descripcion">Descripción</label>
    <input class="form-control" type="text" name="descripcion" id="descripcion" value="<?php echo $plan[0]['descripcion'];?>">

    <label class="form-label" for="monto">Monto de la Cuota</label>
    <input class="form-control" type="number" step="0.01" name="monto" id="monto" value="<?php echo $plan[0]['monto'];?>" required>

    <br>
    <input class="btn btn-success" type="submit" value="Actualizar">
    <a class="btn btn-danger" href="<?php echo base_url(); ?>public/planes">Cancelar</a>
</form>