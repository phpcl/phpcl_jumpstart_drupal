<?php
namespace Drupal\phpcl_test\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Test block
 *
 * @Block(
 *   id = "phpcl_test_test",
 *   admin_label = @Translation("Test"),
 *   category = @Translation("phpcl_test")
 * )
 */
class TestBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('<b>TEST BLOCK</b>'),
    ];
    return $build;
  }

}
