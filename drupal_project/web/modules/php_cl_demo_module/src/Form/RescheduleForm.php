<?php
namespace Drupal\php_cl_demo_module\Form;

use Drupal\Core\Form\ {FormBase,FormStateInterface};
use Drupal\Core\Database\Database;

/**
 * Reschedule Form
 */
class RescheduleForm extends FormBase
{

    const SUCCESS_FORM     = 'SUCCESS: form submitted successfully';
    const SUCCESS_DEL      = 'SUCCESS: event deleted successfully';
    const ERROR_FORM       = 'ERROR: unable to submit form';
    const ERROR_KEY        = 'ERROR: invalid event key';
    const ERROR_DATE       = 'ERROR: date must be in this format: "YYYY-MM-DD"';
    const ERROR_TIME       = 'ERROR: time must be in this format: "HH:MM:SS"';

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
        // filters
        $stripTags = function ($val) { return strip_tags($val); };
        $toUpper   = function ($val) { return strtoupper($val); };
        // form definition
        $form = [
            'event_key' => [
                '#title'    => $this->t('Event Key'),
                '#type'     => 'textfield',
                '#required' => TRUE,
                '#value_callback'   => [ $stripTags, $toUpper ],
            ],
            // docs: https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Datetime!Element!Datetime.php/function/Datetime%3A%3AprocessDatetime/9.0.x
            'event_date_date' => [
                '#title'    => $this->t('Event Date'),
                '#type'     => 'date',
                '#required' => TRUE,
                '#default_value' => date('Y-m-d'),
                '#value_callback'    => [ $stripTags ],
            ],
            'event_date_time' => [
                '#title'    => $this->t('Event Time'),
                '#type'     => 'textfield',
                '#required' => TRUE,
                '#default_value' => date('H:i:s'),
                '#value_callback'    => [ $stripTags ],
            ],
            'submit' => [
                '#title'    => $this->t('Submit'),
                '#type'     => 'submit',
                '#value'    => $this->t('Submit'),
            ],
            'delete' => [
                '#title'    => $this->t('Delete'),
                '#type'     => 'submit',
                '#value'    => $this->t('Delete'),
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
        // example: 'FLO-RES-YR-406'
        $pattern = '/^[A-Z]{3}-[A-Z]{3}-[A-Z]{2}-\d{3}$/';
        if (!preg_match($pattern, $form_state->getValue('event_key'))) {
            $form_state->setErrorByName('event_key', $this->t(self::ERROR_KEY));
        }
        // example: '2020-11-14 00:00:00'
        $expected = 2;
        $actual   = 0;
        $ptnDate  = '/^\d{4}\-\d{2}\-\d{2}$/';
        $ptnTime  = '/^\d{2}:\d{2}:\d{2}$/';
        $date     = $form_state->getValue('event_date_date');
        $time     = $form_state->getValue('event_date_time');
        $actual   += (int) preg_match($ptnDate, $date);
        $actual   += (int) preg_match($ptnTime, $time);
        if ($expected != $actual) {
            $form_state->setErrorByName('event_date_date', $this->t(self::ERROR_DATE));
            $form_state->setErrorByName('event_date_time', $this->t(self::ERROR_TIME));
            error_log(__METHOD__ . ':DATE:' . var_export($form_state->getValues(), TRUE));
        }
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $state)
    {
        $conn = Database::getConnection('default', 'jumpstart');
        // lookup event key
        $key  = $state->getValue('event_key');
        $date = $state->getValue('event_date_date')
              . ' ' . $state->getValue('event_date_time');
        $select = $conn->select('events', 'e');
        $select->fields('e', ['event_name','event_date']);
        $select->condition('e.event_date', $date, '=');
        $result = FALSE;
        $stmt   = $select->execute();
        // only proceed if event key is found
        if ($stmt) {
            // check to see if delete button pressed
            if ($state->getValue('delete', NULL)) {
                $result = $conn->delete('events')
                               ->condition('event_key', $key, '=')
                               ->execute();
                if ($result) {
                    $this->messenger()->addStatus($this->t(self::SUCCESS_DEL));
                }
                error_log(__METHOD__ . ':DELETE:' . var_export($state->getValues(), TRUE));
            } else {
                // perform database update
                $result = $conn->update('events')
                               ->fields(['event_date' => $date])
                               ->condition('event_key', $key, '=')
                               ->execute();
                if ($result) {
                    $this->messenger()->addStatus($this->t(self::SUCCESS_FORM));
                } else {
                    $this->messenger()->addStatus($this->t(self::ERROR_FORM));
                    error_log(__METHOD__ . ':KEY:' . $key . ':DATE:' . var_export($date, TRUE));
                }
            }
        }
    }

}
