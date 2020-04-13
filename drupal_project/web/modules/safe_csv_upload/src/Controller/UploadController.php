<?php
namespace Drupal\safe_csv_upload\Controller;
use Drupal\Core\Controller\ControllerBase;
class UploadController extends ControllerBase
{
    protected $fields;
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('safe-csv-upload-form')
        );
    }
    public function showForm()
    {
        return ['form' => 'TEST'];
    }
}
