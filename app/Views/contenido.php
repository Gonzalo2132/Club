<h3>Soy el contenido</h3>

<?php
    foreach ($clientes as $cliente){
        echo '<p class="cliente">'. $cliente['denominacion']. '</p>';
        echo '<br>';
    }

   // echo base_url(); lo trajimos de esta forma porque es un metodo, igual dudo tener que hacerlo de nuevo
?>