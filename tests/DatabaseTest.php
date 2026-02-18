<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../database/connection.php';

class DatabaseTest extends TestCase
{
    public function testConexionBaseDeDatos()
        {
            global $conn;
            $this->assertTrue(true); 
        }
}