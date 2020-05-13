<?php
namespace Drupal\phpcl_test\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Login form inside a block
 */
class LoginBlockForm extends FormBase
{

    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'phpcl_test_login_block_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $stripTags = function ($val) { return strip_tags($val); };
        $form['username'] = [
            '#type'     => 'textfield',
            '#title' => $this->t('Username'),
            '#description' => $this->t('Enter username'),
            '#value_callback' => [ $stripTags ],
        ];

        // How many phrases?
        $form['password'] = [
            '#type' => 'password',
            '#title' => $this->t('Password'),
            '#description' => $this->t('Enter password'),
        ];

        // Submit.
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Login'),
        ];

        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $username = $form_state->getValue('username');
        if (!ctype_alnum($username)) {
            $form_state->setErrorByName('username', $this->t('Invalid username'));
        }

        $password = $form_state->getValue('password');
        if (empty($password)) {
            $form_state->setErrorByName('password', $this->t('Please enter a valid password'));
        }
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        /* do something */
    }
}