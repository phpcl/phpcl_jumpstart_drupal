<?php

namespace Drupal\phpcl_demo_two\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "phpcl_demo_two_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("phpcl_demo_two")
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
