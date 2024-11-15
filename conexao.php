<?php

    $pdo = new PDO('mysql:host=localhost;dbname=clinicamedica', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
