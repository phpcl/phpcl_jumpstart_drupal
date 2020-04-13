<?php

namespace Drupal\php_cl_demo_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "php_cl_demo_module_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("PHP-CL Demo Module")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
