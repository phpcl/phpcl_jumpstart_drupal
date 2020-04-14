<?php
namespace Drupal\php_cl_demo_module\Form;

use Drupal\Core\Form\ {FormBase,FormStateInterface};

/**
 * Generic Signup Form
 */
class SignupForm extends FormBase
{

    const SUCCESS_FORM     = 'SUCCESS: form submitted successfully';
    const ERROR_EMAIL      = 'ERROR: invalid email address';
    const ERROR_EMAIL_LEN  = 'ERROR: email address needs to be 128 chars or less';
    const ERROR_NAME_LEN   = 'ERROR: username needs to be 8 chars or less';
    const ERROR_NAME_ALNUM = 'ERROR: username can only contain letters or numbers, no spaces';
    const ERROR_GENDER     = 'ERROR: gender not listed';
    const DATA_DIR         = __DIR__ . '/../../../../data';
    const ALLOWED_GENDER   = ['M','F','X'];

    /**
     * Returns a unique string identifying the form.
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId()
    {
        return str_replace('\\', '_', __CLASS_);
    }

    /**
     * Form constructor.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // validation per element
        $validators = [
            'email' => function ($email, $form_state) {
                if (strlen($email) > 128)
                    $form_state->setErrorByName('email', $this->t(self::ERROR_EMAIL_LEN));
            },
            'gender' => function ($gender, $form_state) {
                if (!in_array($gender, self::ALLOWED_GENDER))
                    $form_state->setErrorByName('gender', $this->t(self::ERROR_GENDER));
            },
            'test' => function ($test, $form_state) {
                if (strpos($test, 'TEST') !== FALSE)
                    $form_state->setErrorByName('test', __METHOD__);
            },
            // other validators not shown
        ];
        // filters
        $stripTags = function ($val) { return strip_tags($val); };
        $filters = [
            'email'    => $stripTags,
            'username' => $stripTags,
            'test'     => $stripTags,
            'gender'   => function ($list) {
                foreach ($list as $key => $val) {
                    $list[$key] = strtoupper(substr($val, 0, 1));
                    return $list;
                }
            },
            // other filters not shown
        ];
        // form definition
        $form = [
            'username' => [
                '#title'    => $this->t('User Name'),
                '#type'     => 'textfield',
                '#required' => TRUE,
                '#value_callback'   => [ $filters['username'] ],
            ],
            'email' => [
                '#title'    => $this->t('Email Address'),
                '#type'     => 'email',
                '#required' => TRUE,
                '#element_validate' => [ $validators['email'] ],
                '#value_callback'   => [ $filters['email'] ],
            ],
            'gender' => [
                '#title'    => $this->t('Gender'),
                '#type'     => 'checkboxes',
                '#options'  => ['M' => $this->t('Male'), 'F' => $this->t('Female'), 'X' => $this->t('Other')],
                '#value_callback'   => [ $filters['gender'] ],
            ],
            'test' => [
                '#title'    => $this->t('Test'),
                '#type'     => 'textfield',
                '#element_validate' => [ $validators['test'] ],
                '#value_callback'   => [ $filters['test'] ],
            ],
            'submit' => [
                '#title'    => $this->t('Submit'),
                '#type'     => 'submit',
                '#value'    => $this->t('Submit'),
            ],
        ];
        return $form;
    }

    /**
     * Form validation handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // code to validate submitted form data
        $validators = [
            'email' => function ($email, $form_state) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $form_state->setErrorByName('email', $this->t(self::ERROR_EMAIL));
                }
            },
            // username needs to be 8 chars or less, alpha numeric only
            'username' => function ($name, $form_state) {
                if (strlen($name) > 8) {
                    $form_state->setErrorByName('username', $this->t(self::ERROR_NAME_LEN));
                }
                if (!ctype_alnum($name)) {
                    $form_state->setErrorByName('username', $this->t(self::ERROR_NAME_ALNUM));
                }
            },
            'test' => function ($test, $form_state) {
                if (strpos($test, 'TEST') !== FALSE)
                    $form_state->setErrorByName('test', __METHOD__);
            },
        ];
        foreach ($form_state->getValues() as $key => $item) {
            if (isset($validators[$key]))
                $validators[$key]($item, $form_state);
        }
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->messenger()->addStatus($this->t(self::SUCCESS_FORM));
        $fn = md5($form_state->getValue('email'));
        $fn = str_replace('//', '/', self::DATA_DIR . '/' . $fn);
        file_put_contents($fn, serialize($form_state->getValues()));
    }

}
