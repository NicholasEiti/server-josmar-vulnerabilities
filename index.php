<?php
// inicialização do banco de dados usando a classe PDO do php
function startPDO($name, $host, $charset, $login, $password) {
    try {
        $pdo = new PDO("mysql:dbname=$name;host=$host;chartset=utf8", $login, $password);
    } catch (Exception $e) {
        return False;
    }

    return $pdo;
}

/**
 * Classe para o banco de dados
 */
class DBRequest
{
    static function search(string $tablename, string $sql = null, array $params = null)
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT * FROM $tablename $sql");
        $index->execute($params);

        return $index->fetch(PDO::FETCH_ASSOC);
    }

    static function update(string $tablename, array|string $ids = null, array $params = [])
    {
        global $GLOBAL_PDO;

        $values = implode(", ", array_map(function ($column) {
            return "$column = :$column";
        }, array_keys($params)));

        if (is_array($ids))
            $ids = implode(', ', $ids);

        $sql_where = "WHERE id IN ($ids)";

        $sth = $GLOBAL_PDO->prepare("UPDATE $tablename SET $values $sql_where");
        return $sth->execute($params);
    }

    static function insert(string $tablename, array $params = []) {
        global $GLOBAL_PDO;

        $columns = implode(",", array_keys($params));
        $values = ":" . implode(",:", array_keys($params));

        var_dump("INSERT INTO $tablename ($columns) VALUES ($values)", $params);

        $sth = $GLOBAL_PDO->prepare("INSERT INTO $tablename ($columns) VALUES ($values)");

        return $sth->execute($params);
    }
}

// o ideal é não deixar informações importantes dentro do código
// tipo isso:
$GLOBAL_PDO = startPDO('josmardb', 'josmar_db', 'utf8', 'root', 'root_password');

var_dump($GLOBAL_PDO);
