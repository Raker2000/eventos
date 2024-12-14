<?php
function genToken($longitud = 20)
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $stringAleatorio = '';

    for ($i = 0; $i < $longitud; $i++) {
        $stringAleatorio .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }

    return $stringAleatorio;
}
