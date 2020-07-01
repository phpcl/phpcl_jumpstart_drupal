<?php
namespace Drupal\php_cl_demo_module\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'Test' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Test Block"),
 *   category = @Translation("Test Block"),
 * )
 */
class TestBlock extends BlockBase
{
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('<b>TEST</b>')
    ];
  }

}
