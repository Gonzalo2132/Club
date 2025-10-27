<form action="<?php echo base_url();?>public/login/login" method="post">

    <div class="form-group">
        <input type="email" name="email" class="form-control form-control-user"
            placeholder="Correo electrónico" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control form-control-user"
            placeholder="Contraseña" required>
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
        Ingresar
    </button>


    <?php echo base_url(); ?>public/login">Cancelar</a>

</form>