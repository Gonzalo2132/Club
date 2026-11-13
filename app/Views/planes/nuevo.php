<form action="<?php echo base_url(); ?>public/planes/guardar" method="post">

    <label class="form-label" for="nombre">Nombre (Ej: General, Jubilado)</label>
    <input class="form-control" type="text" name="nombre" id="nombre" required placeholder="Ej: Socio General">

    <label class="form-label" for="descripcion">Descripción</label>
    <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="Detalle del plan (opcional)">

    <br>
    <input class="btn btn-success" type="submit" value="Guardar">
    <a class="btn btn-danger" href="<?php echo base_url(); ?>public/planes">Cancelar</a>
</form>