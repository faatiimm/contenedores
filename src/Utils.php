<?php

class Utils
{
    public static function sumar($a, $b)
    {
        return $a + $b;
    }

    public static function esEmailValido($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function esMayorDeEdad($edad)
    {
        return $edad >= 18;
    }
}
