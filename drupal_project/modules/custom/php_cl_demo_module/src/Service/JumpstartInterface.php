<?php
namespace Drupal\php_cl_demo_module\Service;

/**
 * Service class giving access to the `jumpstart.users` table
 */
interface JumpstartInterface
{
    const DB_NAME = 'jumpstart';
    abstract public function fetchAll();
    abstract public function findById(int $id);
}
