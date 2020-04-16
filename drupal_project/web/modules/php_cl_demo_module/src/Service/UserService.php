<?php
namespace Drupal\php_cl_demo_module\Service;

use Drupal\Core\Database\Database;

/**
 * Service class giving access to the `jumpstart.users` table
 */
class UserService
{
    const TABLE_NAME = 'users';
    const DB_NAME = 'jumpstart';
    protected $connection;
    public function __construct()
    {
         $this->connection = Database::getConnection('default', self::DB_NAME);
    }

}
