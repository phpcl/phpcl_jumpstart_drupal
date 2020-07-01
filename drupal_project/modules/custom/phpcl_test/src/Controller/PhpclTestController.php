<?php
namespace Drupal\phpcl_test\Controller;
use Drupal\Core\Controller\ControllerBase;
/**
 * Returns responses for phpcl_test routes.
 */
class PhpclTestController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build()
  {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
