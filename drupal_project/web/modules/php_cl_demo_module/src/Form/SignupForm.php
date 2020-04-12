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
        // validators
        $validators = [
            'email' => function ($email) { return filter_var($email, FILTER_VALIDATE_EMAIL); },
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
