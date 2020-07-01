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
  protected $dates = [];

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


  protected function getSearchDates()
  {
    $start_date = new \DateTime();
    $end_date   = new \DateTime();
    $end_date->add(new \DateInterval('P1Y'));
    $this->dates['start'] = $start_date->format('Y-m-d');
    $this->dates['stop']  = $end_date->format('Y-m-d');
    return $this->dates;
  }

  /**
   * Test
   */
  public function test() {

    $dates = $this->getSearchDates();
    $html = '<h1>Signup from Controller</h1>'
        . '<br><a href="/php-cl-demo-module/test/signup">Signup</a>'
        . '<br><a href="/php-cl-demo-module/db-simple-query">Simple DB Query</a>'
        . '<br><a href="/php-cl-demo-module/db-dynamic-query">Dynamic DB Query</a>'
        . '<br><a href="/php-cl-demo-module/db-dynamic-query-simple-condition/0/' . $dates['start'] . '/' . $dates['stop'] . '">Dynamic Query Simple Condition</a>'
        . '<br><a href="/php-cl-demo-module/test/get-params">Grab Parameters</a>'
        . '<br><a href="/php-cl-demo-module/use-config">Use Default Config</a>';

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
        $limit = self::LINES_PER_PAGE;
        $offset = (int) $page * $limit;
        $conn = Database::getConnection('default', 'jumpstart');
        $select = $conn->select('events', 'e');
        $select->fields('e', ['event_key','event_date','hotel_id']);
        $select->join('hotels','h','e.hotel_id = h.id');
        $select->fields('h', ['hotelName','city','country']);
        $select->orderBy('e.event_date', 'ASC');
        $select->range($offset, $limit);
        $stmt   = $select->execute();
        $output .= '<hr><pre>';
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            array_walk($row, function (&$val) { $val = substr($val, 0, 15); });
            $output .= vsprintf('%15s | %15s | %4d | %15s | %15s | %2s' . PHP_EOL, $row);
        }
        $output .= '</pre><hr><a href="/php-cl-demo-module/db-dynamic-query/' . ++$page . '">Next Page</a>';
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
   * Dynamic query with simple conditions
   */
  public function db_dynamic_query_simple_condition($page = 0, $start_date = NULL, $end_date = NULL) {
    $output = '';
    $dates  = $this->getSearchDates();
    $start_date = $start_date ?? $dates['start'];
    $end_date   = $end_date   ?? $dates['stop'];
    $start_date = strip_tags($start_date);
    $end_date   = strip_tags($end_date);
    try {
        $limit = self::LINES_PER_PAGE;
        $offset = (int) $page * $limit;
        $conn = Database::getConnection('default', 'jumpstart');
        $select = $conn->select('events', 'e');
        $select->fields('e', ['event_key','event_name','event_date']);
        $select->orderBy('e.event_date', 'ASC');
        $select->range($offset, $limit);
        $select->condition('e.event_date', $start_date, '>=');
        $select->condition('e.event_date', $end_date, '<');
        $stmt   = $select->execute();
        $output .= '<hr><pre>';
        #error_log(__METHOD__ . ':SQL: ' . $select);
        #error_log(__METHOD__ . ':Placeholders: ' . var_export($select->getArguments(), TRUE));
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $output .= vsprintf('%16s | %30s | %20s' . PHP_EOL, $row);
        }
        $url = '/php-cl-demo-module/db-dynamic-query-simple-condition/'
             . ++$page . '/' . $start_date . '/' . $end_date;
        $output .= '</pre><hr><a href="' . $url . '">Next Page</a>';
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
   * Demonstrates accessing default config params stored in:
   * php_cl_demo_module/config/install/php_cl_demo_module.settings.yml
   */
  public function use_config()
  {
    $config  = \Drupal::config('php_cl_demo_module.settings');
    $settings = ['company','website','contact'];
    $output  = '<table>';
    foreach ($settings as $item) {
        $output .= '<tr><th>' . ucfirst($item) . '</th><td>';
        $output .= $config->get($item);
        $output .= '</td></tr>';
    }
    $output .= '</table>';
    $build['content'] = ['#type' => 'item','#markup' => $output];
    return $build;
  }


}
