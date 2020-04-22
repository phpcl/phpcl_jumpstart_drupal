<?php
namespace Drupal\php_cl_demo_module\Form;

use Drupal\Core\Form\ {FormBase,FormStateInterface};
use Drupal\Core\Database\Database;

/**
 * Generic Signup Form
 */
class SignupForm extends FormBase
{

    const SUCCESS_FORM     = 'SUCCESS: form submitted successfully';
    const ERROR_FORM       = 'ERROR: unable to submit form';
    const ERROR_EMAIL      = 'ERROR: invalid email address';
    const ERROR_EMAIL_LEN  = 'ERROR: email address needs to be 128 chars or less';
    const ERROR_NAME_LEN   = 'ERROR: name needs to be 24 chars or less';
    const ERROR_NAME_ALNUM = 'ERROR: name can only contain letters or numbers, or spaces';
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
            'email'      => $stripTags,
            'first_name' => $stripTags,
            'first_name' => $stripTags,
            'gender'     => function ($gender) { return strtoupper(substr($gender, 0, 1)); },
            // other filters not shown
        ];
        // form definition
        $form = [
            'first_name' => [
                '#title'    => $this->t('First Name'),
                '#type'     => 'textfield',
                '#required' => TRUE,
                '#value_callback'   => [ $filters['first_name'] ],
            ],
            'last_name' => [
                '#title'    => $this->t('Last Name'),
                '#type'     => 'textfield',
                '#required' => TRUE,
                '#value_callback'   => [ $filters['last_name'] ],
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
            // first and last names needs to be alpha numeric or space only + < 24 chars in length
            'first_name' => function ($name, $form_state) {
                if (strlen($name) > 24) {
                    $form_state->setErrorByName('first_name', $this->t(self::ERROR_NAME_LEN));
                }
                $temp = str_replace(' ', '', $name);
                if (!ctype_alnum($temp)) {
                    $form_state->setErrorByName('first_name', $this->t(self::ERROR_NAME_ALNUM));
                }
            },
            'last_name' => function ($name, $form_state) {
                if (strlen($name) > 24) {
                    $form_state->setErrorByName('last_name', $this->t(self::ERROR_NAME_LEN));
                }
                $temp = str_replace(' ', '', $name);
                if (!ctype_alnum($temp)) {
                    $form_state->setErrorByName('last_name', $this->t(self::ERROR_NAME_ALNUM));
                }
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
        $conn   = Database::getConnection('default', 'jumpstart');
        // extract gender info from form array
        $gender = array_filter($vals, function ($i) { return (bool) $i; });
        // create user key
        $first   = $form_state->getValue('first_name');
        $last    = $form_state->getValue('last_name');
        $userKey = strtoupper(substr($first, 0, 4) . substr($last, 0, 4)) . rand(1000,9999);
        // perform database insert
        $result = $conn->insert('users')
                       ->fields([
                            'userKey'    => $userKey,
                            'email'      => $form_state->getValue('email'),
                            'first_name' => $first,
                            'last_name'  => $last,
                            'gender'     => array_pop($gender),
                        ])->execute();
        if ($result) {
            $this->messenger()->addStatus($this->t(self::SUCCESS_FORM));
        } else {
            $this->messenger()->addStatus($this->t(self::ERROR_FORM));
        }
        error_log(__METHOD__ . ':' . var_export($result, TRUE));
    }

}
