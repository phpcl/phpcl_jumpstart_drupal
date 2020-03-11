<?php

namespace Drupal\php_cl_demo_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for PHP-CL Demo Module routes.
 */
class PhpClApiController extends ControllerBase {

    /**
     * Responds based on HTTP method
     */
    public function methodTest()
    {
        $data = array_combine(range('A','F'), range(10,60,10));
        $result = ['status' => 'success', 'info' => __METHOD__, 'data' => $data];
        return new JsonResponse($result);
    }
    /**
     * Responds based on HTTP headers
     */
    public function headerTest()
    {
        $data = array_combine(range('A','F'), range(10,60,10));
        $result = ['status' => 'success', 'info' => __METHOD__, 'data' => $data];
        return new JsonResponse($result);
    }
}
