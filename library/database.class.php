<?php
/**
 * Classes para o banco de dados
 */

define('EMAIL_PATTERN', "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/");
define('DATETIME_PATTERN', '/^([0-9]{4})-([0-1][0-9])-([0-3][0-9])\s([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$/');
$ENUM_REQUEST_STATUS = [
    'not_started'   => 1, // antes do date_start
    'started'       => 3, // user pegou a chave
    'ended'         => 4, // depois do date_end => usuario devolveu a chave
    'canceled'      => 5, // pedido cancelado
];
$ENUM_USER_LEVELS    = [ 'collaborator' => 5, 'admin' => 15 ];

function startPDO($name, $host, $charset, $login, $password): PDO
{
    try {
        $pdo = new PDO("mysql:dbname=$name;host=$host;chartset=$charset", $login, $password);
    } catch (Exception $e) {
        API::send_error('database_connection_error');
    }

    return $pdo;
}

abstract class ColumnDB {
    /** @var string $tablename */
    static public $tablename;

    static function count(string $sql = null, array $params = null): int
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT id FROM `" . static::$tablename . "` $sql");
        $index->execute($params);

        return $index->rowCount();
    }

    static function search(string $sql = null, array $params = null): array|false
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT * FROM `" . static::$tablename . "` $sql");
        $index->execute($params);

        $rows = $index->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0)
            return False;
        
        return $rows;
    }

    static function searchById($id) {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT * FROM `" . static::$tablename . "` WHERE id = :id");
        $index->execute([':id' => $id]);

        return $index->fetch(PDO::FETCH_ASSOC);
    }

    static function update(array|string $ids, array $params = []): bool
    {
        global $GLOBAL_PDO;

        $values = implode(", ", array_map(fn ($column) => "`$column` = :$column", array_keys($params)));

        if (is_array($ids))
            $ids = implode(', ', $ids);

        $index = $GLOBAL_PDO->prepare("UPDATE `" . static::$tablename . "` SET $values WHERE id IN ($ids)");
        return $index->execute($params);
    }

    static function insert(array $params = []): bool
    {
        global $GLOBAL_PDO;

        $columns = '`' . implode("`, `", array_keys($params)) . '`';
        $values = ":" . implode(",:", array_keys($params));

        $index = $GLOBAL_PDO->prepare("INSERT INTO `" . static::$tablename . "` ($columns) VALUES ($values)");

        return $index->execute($params);
    }

    static function delete(string $sql = null, array $params = []): bool
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("DELETE FROM `" . static::$tablename . "` $sql");

        return $index->execute($params);
    }

    static function deleteById(array|int $ids): bool
    {
        global $GLOBAL_PDO;

        if (is_array($ids)) {
            $id_count = count($ids);

            if ($id_count != 1) {
                $index = $GLOBAL_PDO->prepare("DELETE FROM `" . static::$tablename . "` WHERE id IN (?" . str_repeat(', ?', $id_count - 1) . ")");
                return $index->execute(array_values($ids));
            }

            $ids = $ids[0];
        }
    
        $index = $GLOBAL_PDO->prepare("DELETE FROM `" . static::$tablename . "` WHERE id = :id");
        return $index->execute([ ':id' => $ids ]);
    }

    static function hasId(int $id): bool
    {
        return static::searchById($id) !== False;
    }

    static function get_last_id(): int
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->query("SELECT id FROM `" . static::$tablename . "` ORDER BY id DESC LIMIT 1");
        $rows = $index->fetch(PDO::FETCH_ASSOC);

        return $rows['id'];
    }
}

class DrawerDB  extends ColumnDB { static public $tablename = 'drawers';    }
class KeyDB     extends ColumnDB { static public $tablename = 'keys';       }
class UserDB    extends ColumnDB { static public $tablename = 'users';      }
class RequestDB extends ColumnDB { static public $tablename = 'requests';   }
