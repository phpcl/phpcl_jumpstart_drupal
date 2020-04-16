<?php

namespace Drupal\php_cl_demo_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\php_cl_demo_module\Form\SignupForm;
use Drupal\Core\Database\Database;

/**
 * Returns responses for PHP-CL Demo Module routes.
 */
class PhpClDemoModuleController extends ControllerBase
{

  const LINES_PER_PAGE = 12;
  const ERROR_DB = 'ERROR: problem with database connection';
  /**
   * Builds the response.
   */
  public function example() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

  /**
   * Demonstrates accessing $_GET params in a controller
   */
  public function get_params() {
    $request = \Drupal::request();
    $name = $request->query->get('name', $this->t('Unknown'));
    $build['content'] = [
      '#type' => 'item',
      '#markup' => 'Name: ' . htmlspecialchars($name)
          . '<br>Try entering this URL: /php-cl-demo-module/test/get-params?name=TEST',
    ];
    return $build;
  }


  /**
   * Test
   */
  public function test() {

    $html = '<h1>Signup from Controller</h1>'
        . '<br><a href="/php-cl-demo-module/test/signup">Signup</a>'
        . '<br><a href="/php-cl-demo-module/db-simple-query">Simple DB Query</a>'
        . '<br><a href="/php-cl-demo-module/db-dynamic-query">Dynamic DB Query</a>'
        . '<br><a href="/php-cl-demo-module/test/get-params">Grab Parameters</a>';

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $html,
    ];

    return $build;
  }

  /**
   * Sub Test 1
   */
  public function subTest1() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t(__METHOD__),
    ];

    return $build;
  }

  /**
   * Sub Test 2
   */
  public function subTest2() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t(__METHOD__),
    ];

    return $build;
  }

  /**
   * Returns information about city and country code
   */
  public function location($city = 'Unknown', $code = 'CA') {
    $city = strip_tags($city);
    $code = strtoupper(substr(strip_tags($code), 0, 2));
    $output = 'City : ' . $city . '<br>Country: ' . $code;
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t($output),
    ];
    return $build;
  }

  /**
   * Returns pagination information
   */
  public function pagination($page) {
    $page = (int) $page;
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Page Number: ' . $page),
    ];
    return $build;
  }

  /**
   * Demonstrates using a form in a controller
   */
  public function signup() {
    $form = \Drupal::formBuilder()->getForm(SignupForm::class);
    return $form;
  }

  /**
   * Demonstrates using a database connection in a controller
   * Simple query
   */
  public function db_simple_query($page = 0) {
    $output = '';
    try {
        $limit = self::LINES_PER_PAGE;
        $offset = $page * $limit;
        $conn = Database::getConnection('default', 'jumpstart');
        $sql = sprintf('SELECT * FROM users ORDER BY last_name LIMIT %d OFFSET %d', $limit, $offset);
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $output .= get_class($stmt);
        if ($result) {
            $output .= '<hr>';
            $output .= '<ul>';
            foreach ($result as $row) {
                $output .= sprintf('<li>%s %s %s %s</li>',
                    $row['title'], $row['first_name'], $row['middle_name'], $row['last_name']);
            }
            $output .= '</ul><hr><a href="/php-cl-demo-module/db-simple-query/' . ++$page . '">Next Page</a>';
        }
    } catch (\Exception $e) {
        error_log(__METHOD__ . ':' . $e->getMessage());
        $output = self::ERROR_DB;
    }
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];
    return $build;
  }

  /**
   * Demonstrates using a database connection in a controller
   * Dynamic query
   */
  public function db_dynamic_query($page = 0) {
    $output = '';
    try {
        $conn = Database::getConnection('default', 'jumpstart');
        $select = $conn->select('users');
        $output = get_class($select);
    } catch (\Exception $e) {
        error_log(__METHOD__ . ':' . $e->getMessage());
        $output = self::ERROR_DB;
    }
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];
    return $build;
  }
}
