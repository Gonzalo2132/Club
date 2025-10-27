<form action="<?php echo base_url();?>public/login/validar" method="post">

    <div class="form-group">
        <input type="text" name="username" class="form-control form-control-user"
            placeholder="Nombre de Usuario" required>
    </div>
    <div class="form-group">
        <input type="password" name="clave" class="form-control form-control-user"
            placeholder="Contraseña" required>
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
        Ingresar
    </button>
    <a class="btn btn-danger btn-user btn-block" href="<?php echo base_url();?>public/panel">Cancelar</a>

</form>