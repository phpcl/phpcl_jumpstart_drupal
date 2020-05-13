<?php
namespace Drupal\php_cl_demo_module\Service;

use Drupal\Core\Database\Database;

/**
 * Service class giving access to the `jumpstart.users` table
 */
abstract class JumpstartBase implements JumpstartInterface
{
    const TABLE_NAME = '';
    protected $connection;
    public function __construct()
    {
         $this->connection = Database::getConnection('default', JumpstartInterface::DB_NAME);
    }
    public function fetchAll()
    {
        $sql = 'SELECT * FROM ' . static::TABLE_NAME;
        return $this->connection->query($sql);
    }
    public function findById(int $id, $fetchMode = PDO::FETCH_ASSOC)
    {
        $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch($fetchMode);
    }
}
