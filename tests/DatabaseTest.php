<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../database/connection.php';

class DatabaseTest extends TestCase
{
    public function testConexionBaseDeDatos()
    {
        global $conn;

        $this->assertFalse($conn->connect_error, 
            "La conexión a la base de datos falló: " . $conn->connect_error
        );

        $resultado = $conn->query("SELECT 1");

        $this->assertNotFalse($resultado);
        $this->assertEquals(1, $resultado->fetch_row()[0], 
            "La consulta de prueba no devolvió el resultado esperado."
        );
    }
}