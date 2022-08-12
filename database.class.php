<?php
/**
 * Classe para o banco de dados
 */
class DBRequest
{
    static function startPDO($name, $host, $charset, $login, $password): PDO
    {
        try {
            $pdo = new PDO("mysql:dbname=$name;host=$host;chartset=utf8", $login, $password);
        } catch (Exception $e) {
            API::send_error([
                'msg' => 'Database connection error.'
            ]);
        }
    
        return $pdo;
    }

    static function count(string $tablename, string $sql = null, array $params = null): int
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT id FROM $tablename $sql");
        $index->execute($params);

        return $index->rowCount();
    }

    static function search(string $tablename, string $sql = null, array $params = null): mixed
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT * FROM $tablename $sql");
        $index->execute($params);

        return $index->fetch(PDO::FETCH_ASSOC);
    }

    static function update(string $tablename, array|string $ids = null, array $params = []): bool
    {
        global $GLOBAL_PDO;

        $values = implode(", ", array_map(function ($column) {
            return "$column = :$column";
        }, array_keys($params)));

        if (is_array($ids))
            $ids = implode(', ', $ids);

        $index = $GLOBAL_PDO->prepare("UPDATE $tablename SET $values WHERE id IN ($ids)");
        return $index->execute($params);
    }

    static function insert(string $tablename, array $params = []): bool
    {
        global $GLOBAL_PDO;

        $columns = implode(",", array_keys($params));
        $values = ":" . implode(",:", array_keys($params));

        $index = $GLOBAL_PDO->prepare("INSERT INTO $tablename ($columns) VALUES ($values)");

        return $index->execute($params);
    }
    
    static function delete(string $tablename, string $sql = null, array $params = []): bool
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("DELETE FROM $tablename $sql");

        return $index->execute($params);
    }

    static function get_last_id(string $tablename): int
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->query("SELECT id FROM $tablename ORDER BY id DESC LIMIT 1");
        $rows = $index->fetch(PDO::FETCH_ASSOC);

        return $rows['id'];
    }
}

