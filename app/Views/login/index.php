<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Socios CAS</title>
    <link rel="icon" type="image/png" href="<?= base_url('public/img/sarmiento_icon.png') ?>">
    <link href="<?php echo base_url(); ?>public/js/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>public/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Login</h3>
                        <form action="<?php echo base_url(); ?>public/login/validar" method="post">

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

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</body>

</html>