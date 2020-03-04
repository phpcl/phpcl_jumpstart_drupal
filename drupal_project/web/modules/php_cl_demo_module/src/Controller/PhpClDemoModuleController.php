<?php

namespace Drupal\php_cl_demo_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for PHP-CL Demo Module routes.
 */
class PhpClDemoModuleController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
