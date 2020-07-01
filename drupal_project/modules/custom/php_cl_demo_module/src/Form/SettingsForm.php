<?php

namespace Drupal\php_cl_demo_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure PHP-CL Demo Module settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'php_cl_demo_module_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['php_cl_demo_module.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $stripTags = function ($val) { return strip_tags($val); };
    $form = [
        'company' => [
            '#type' => 'textfield',
            '#title' => $this->t('Company'),
            '#default_value' => $this->config('php_cl_demo_module.settings')->get('company'),
            '#value_callback' => [ $stripTags ],
        ],
        'website' => [
            '#type' => 'url',
            '#title' => $this->t('Website'),
            '#default_value' => $this->config('php_cl_demo_module.settings')->get('website'),
            '#value_callback' => [ $stripTags ],
        ],
        'contact' => [
            '#type' => 'email',
            '#title' => $this->t('Contact'),
            '#default_value' => $this->config('php_cl_demo_module.settings')->get('contact'),
            '#value_callback' => [ $stripTags ],
        ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $company = $form_state->getValue('company');
    $company = str_replace([' ','.',',','-'], '', $company);
    if (!ctype_alnum($company)) {
      $form_state->setErrorByName('company', $this->t('Company name must only contain letters, space, comma, period dash'));
    }
    $website = $form_state->getValue('website');
    if (!filter_var($website, FILTER_VALIDATE_URL)) {
      $form_state->setErrorByName('website', $this->t('Website must be a valid URL.'));
    }
    $contact = $form_state->getValue('contact');
    if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('contact', $this->t('Contact must be a valid email address.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('php_cl_demo_module.settings')
      ->set('company', $form_state->getValue('company'))
      ->set('website', $form_state->getValue('website'))
      ->set('contact', $form_state->getValue('contact'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
