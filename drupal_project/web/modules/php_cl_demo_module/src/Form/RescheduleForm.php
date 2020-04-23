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
    const ERROR_FORM       = 'ERROR: unable to submit form';
    const ERROR_KEY        = 'ERROR: invalid event key';
    const ERROR_DATE       = 'ERROR: invalid event date; date must be in this format: YYYY-MM-DD';
    const ERROR_TIME       = 'ERROR: invalid event date; time must be in this format: HH:MM:SS';

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
            'event_date' => [
                '#title'    => $this->t('Event Date'),
                '#type'     => 'datetime',
                '#attributes' => ['placeholder' => t('YYYY-MM-DD')],
                '#required' => TRUE,
                '#value_callback'   => [ $stripTags ],
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
            // example: 'FLO-RES-YR-406'
            'event_key' => function ($key, $form_state) {
                $pattern = '/^[A-Z]{3}-[A-Z]{3}-[A-Z]{2}-\d{3}$/';
                if (!preg_match($pattern, $key)) {
                    $form_state->setErrorByName('event_key', $this->t(self::ERROR_KEY));
                }
            },
            // example: '2020-11-14 00:00:00'
            'event_date' => function ($date, $form_state) {
                $expected = 2;
                $actual   = 0;
                if (!preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $date['date'])) {
                    $form_state->setErrorByName('event_date', $this->t(self::ERROR_DATE));
                } else {
                    $actual++;
                }
                if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $date['time'])) {
                    $form_state->setErrorByName('event_date', $this->t(self::ERROR_TIME));
                } else {
                    $actual++;
                }
                if ($expected !== $actual) {
                    error_log(__METHOD__ . ':DATE:' . var_export($date, TRUE));
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
        // lookup event key
        $key = $form_state->getValue('event_key');
        $date = $form_state->getValue('event_date');
        $select = $conn->select('events', 'e');
        $select->fields('e', ['event_name','event_date']);
        $select->condition('e.event_date', $date, '=');
        $result = FALSE;
        $stmt   = $select->execute();
        // only proceed if event key is found
        if ($stmt && $stmt->rowCount()) {
            // perform database update
            $result = $conn->update('events')
                           ->fields(['event_date' => $date])
                           ->condition('event_key', $key, '=')
                           ->execute();
        }
        if ($result) {
            $this->messenger()->addStatus($this->t(self::SUCCESS_FORM));
        } else {
            $this->messenger()->addStatus($this->t(self::ERROR_FORM));
            error_log(__METHOD__ . ':KEY:' . $key . ':DATE:' . var_export($date, TRUE));
        }
    }

}
