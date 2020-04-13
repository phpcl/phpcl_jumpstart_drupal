<?php

namespace Drupal\php_cl_demo_module\Form;

use Drupal\Core\Form\ {FormBase,FormStateInterface};

/**
 * Generic Signup Form
 */
class SignupForm extends FormBase
{
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
            'email' => function ($email) use ($form_state) {
                if (strlen($email) > 128)
                    $form_state->setErrorByName('email', $this->t(self::ERROR_EMAIL_LEN));
            },
            // other validators not shown
        ];
        // filters
        $filters = [
            'email' => function ($email) { return strip_tags($email); },
            // other filters not shown
        ];
        // form definition
        $form = [
            'email' => [
                '#title'    => 'Email Address',
                '#type'     => 'email',
                '#required' => TRUE,
                '#element_validate' => [ $validators['email'] ],
                '#value_callback'   => [ $filters['email'] ],
            ],
            // other elements not shown
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
            'email' => function ($email) {
                $valid = TRUE;
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $valid = FALSE;
                    $form_state->setErrorByName('email', $this->t(self::ERROR_EMAIL));
                }
                return $valid;
            },
            // other validators not shown
        ];
        foreach ($form_state->getValues() as $key => $item) {
            $validators[$key]();
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
        // code to process form data post-validation
    }

}
