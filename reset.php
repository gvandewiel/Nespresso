<?php
    // configuration
    include 'config.php';

    $query = "UPDATE flavours SET pos = 0";
    $result = $db->query($query);

    $query = "UPDATE flavours SET neg = 0";
    $result = $db->query($query);

    header("Location: /Nespresso");
?>