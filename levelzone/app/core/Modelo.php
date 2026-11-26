<?php
class Modelo {
    protected PDO $bd;
    function __construct() { $this->bd = Banco::pdo(); }
    protected function todos($sql, $args = []) { $s = $this->bd->prepare($sql); $s->execute($args); return $s->fetchAll(); }
    protected function um($sql, $args = []) { $s = $this->bd->prepare($sql); $s->execute($args); return $s->fetch() ?: null; }
    protected function exec($sql, $args = []) { $this->bd->prepare($sql)->execute($args); }
}
