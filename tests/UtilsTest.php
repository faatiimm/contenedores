<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Utils.php';

class UtilsTest extends TestCase
{
    public function testSumar()
    {
        $this->assertEquals(5, Utils::sumar(2, 3));
        $this->assertEquals(0, Utils::sumar(-2, 2));
    }

    public function testEmailValido()
    {
        $this->assertTrue(Utils::esEmailValido("fatima@email.com"));
        $this->assertFalse(Utils::esEmailValido("correo_invalido"));
    }

    public function testMayorDeEdad()
    {
        $this->assertTrue(Utils::esMayorDeEdad(18));
        $this->assertTrue(Utils::esMayorDeEdad(25));
        $this->assertFalse(Utils::esMayorDeEdad(16));
    }
}
