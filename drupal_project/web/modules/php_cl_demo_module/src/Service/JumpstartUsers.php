<?php
namespace Drupal\php_cl_demo_module\Service;

use PDO;
use Drupal\Core\Database\Database;

/**
 * Service class giving access to the `jumpstart.users` table
 */
class JumpstartUsers extends JumpstartBase
{
    const TABLE_NAME = 'users';
}
