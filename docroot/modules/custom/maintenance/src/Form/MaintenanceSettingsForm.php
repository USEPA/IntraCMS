<?php

namespace Drupal\maintenance\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Maintenance settings for this site.
 */
class MaintenanceSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'maintenance_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['maintenance.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('maintenance.settings');

    $form['disable_email'] = [
      '#type' => 'checkbox',
      '#title' => t('Disable sending of site email.'),
      '#default_value' => $config->get('disable_email'),
      '#description' => $this->t('Check this box to disable email sending from the site.'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('maintenance.settings')
      ->set('disable_email', $form_state->getValue('disable_email'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
