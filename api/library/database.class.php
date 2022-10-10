<?php
/**
 * Classes para o banco de dados
 */

define('EMAIL_PATTERN', "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/");
define('DATETIME_PATTERN', '/^([0-9]{4})-([0-1][0-9])-([0-3][0-9])\s([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$/');
define('ADMIN_MIN_LEVEL', 10);

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
    /** @var array $tablename */
    static public $joins = [];

    static function count(string $sql = null, array $params = null): int
    {
        global $GLOBAL_PDO;

        $index = $GLOBAL_PDO->prepare("SELECT id FROM `" . static::$tablename . "` $sql");
        $index->execute($params);

        return $index->rowCount();
    }

    static function search(string $sql = null, array $params = null, array $joins = null): array|false
    {
        global $GLOBAL_PDO;

        $columns = "";
        $join = "";

        if ($joins !== null) {
            foreach ($joins as $join_name => $join_columns) {
                $join_info = static::$joins[$join_name];
                
                $join .= " " . $join_info['join_statement'];
                $join_tablename = $join_info['tablename'];
    
                foreach ($join_columns as $column_name => $column_alias)
                    $columns .= ", `$join_tablename`.`$column_name` as `$column_alias`";
            }
        }

        $index = $GLOBAL_PDO->prepare("SELECT `" . static::$tablename . "`.*$columns FROM `" . static::$tablename . "` $join $sql");
        $index->execute($params);

        $rows = $index->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0)
            return False;
        
        return $rows;
    }

    static function searchById($id, array $joins = null) {
        global $GLOBAL_PDO;

        $columns = "";
        $join = "";

        if ($joins !== null) {
            foreach ($joins as $join_name => $join_columns) {
                $join_info = static::$joins[$join_name];
                
                $join .= " " . $join_info['join_statement'];
                $join_tablename = $join_info['tablename'];
    
                foreach ($join_columns as $column_name => $column_alias)
                    $columns .= ", `$join_tablename`.`$column_name` as `$column_alias`";
            }
        }

        $index = $GLOBAL_PDO->prepare("SELECT `" . static::$tablename . "`.*$columns FROM `" . static::$tablename . "` $join WHERE `" . static::$tablename . "`.`id` = :id");
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

    static function dynamicListSearch(?array $queries = null, ?array $params = null, ?string $order = null, ?int $limit = null, ?int $offset = null, ?array $joins = null) {
        $query = '';

        if ($queries !== null && count($queries) != 0) $query = 'WHERE ' . implode(' and ', $queries);

        $count = static::count($query, $params);

        if ($order !== null) $query .= " ORDER BY $order";
        if ($limit !== null) $query .= " LIMIT $limit";
        if ($offset !== null) $query .= " OFFSET $offset";

        $list = static::search($query, $params, $joins);

        return ['count' => $count, 'list' => $list];
    }
}

class DrawerDB  extends ColumnDB { static public $tablename = 'drawers';    }
class KeyDB     extends ColumnDB {
    static public $tablename = 'keys';
    static public $joins = [
        'drawer' => [
            'tablename' => 'drawers',
            'join_statement' => 'LEFT JOIN `drawers` ON `keys`.`drawer` = `drawers`.`id`'
        ]
    ];
}
class UserDB    extends ColumnDB {
    static public $tablename = 'users';
    const PASSWORD_HASH_COST = 12;

    static function formatEmail(string $email) {
        $matches = null;

        preg_match_all("/^(?P<a>.*)(?P<o>@.*$)/m", $email, $matches, PREG_PATTERN_ORDER);
        
        return strtolower(str_replace('.', '', $matches['a'][0]) . $matches['o'][0]);
    }

    static function formatPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT, [
            'cost' => static::PASSWORD_HASH_COST
        ]);
    }


    static public $ENUM_LEVELS = [
        'inactive'      => 0,
        'collaborator'  => 5,
        'admin'         => 15
    ];
}
class RequestDB extends ColumnDB {
    static public $tablename = 'requests';
    static public $joins = [
        'user' => [
            'tablename' => 'users',
            'join_statement' => 'LEFT JOIN `users` ON `requests`.`user` = `users`.`id`'
        ],
        'key' => [
            'tablename' => 'keys',
            'join_statement' => 'LEFT JOIN `keys` ON `requests`.`key` = `keys`.`id`'
        ]
    ];

    static public $ENUM_STATUS = [
        'not_started'   => 1,
        'start_request' => 2,
        'started'       => 3,
        'end_request'   => 4,
        'ended'         => 5,
        'canceled'      => 6
    ];
}
class SessionDB extends ColumnDB { static public $tablename = 'sessions';   }
