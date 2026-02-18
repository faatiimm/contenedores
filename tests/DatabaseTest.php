<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../database/connection.php';

class DatabaseTest extends TestCase
{
    public function testConexionBaseDeDatos()
    {
        global $conn; // si tu connection.php usa $conn

        $this->assertNotNull($conn);

        $resultado = $conn->query("SELECT 1");

        $this->assertNotFalse($resultado);
    }
}