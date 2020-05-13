<?php
namespace Drupal\phpcl_demo_two\Controller;
use Drupal\Core\Controller\ControllerBase;
/**
 * Returns responses for phpcl_demo_two routes.
 */
class PhpclDemoTwoController extends ControllerBase
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
