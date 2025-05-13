<?php
if (!function_exists('cargarConfig')) {
    function cargarConfig($archivo) {
        return require __DIR__ . "/../../config/$archivo.php";
    }
} 
