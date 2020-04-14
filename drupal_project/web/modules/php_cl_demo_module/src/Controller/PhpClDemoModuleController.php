<?php

namespace Drupal\php_cl_demo_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\php_cl_demo_module\Form\SignupForm;

/**
 * Returns responses for PHP-CL Demo Module routes.
 */
class PhpClDemoModuleController extends ControllerBase {

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
   * Test
   */
  public function test() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => '<h1>Signup from Controller</h1><br><a href="/php-cl-demo-module/test/signup">Signup</a>',
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

}
