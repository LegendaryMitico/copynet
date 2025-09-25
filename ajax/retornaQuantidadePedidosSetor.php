<?php
    date_default_timezone_set('America/Manaus');
    $setores = [];
    
    $pegaIdQuery = "SELECT setorAtual FROM `Esteira` WHERE `setorAtual`='item_$_POST[numeroMolde]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);

    mysqli_num_rows($resultado);
?>


