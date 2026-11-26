<?php
class Banco {
    private static ?PDO $pdo = null;
    static function pdo(): PDO {
        return self::$pdo ??= new PDO(
            'mysql:host=' . BD_HOST . ';dbname=' . BD_NOME . ';charset=utf8mb4', BD_USUARIO, BD_SENHA,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
}
