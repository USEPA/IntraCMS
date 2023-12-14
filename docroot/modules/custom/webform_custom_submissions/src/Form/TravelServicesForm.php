<?php

namespace Drupal\webform_custom_submissions\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class TravelServicesForm extends ConfigFormBase {

  /**
   * @return array
   */
  protected function getEditableConfigNames() {
    return ['webform_custom_submissions.form'];
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'webform_custom_submissions_form';
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   *
   * @return array
   *
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('webform_custom_submissions.form');
    $jira_settings = \Drupal::config('webform_custom_submissions.form');
    $form['BASE_URL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Base Url for Jira'),
      '#default_value' => $jira_settings->get('BASE_URL'),
      '#disabled' => TRUE,
    ];
    $form['REST_URL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Url of REST requests to Jira'),
      '#default_value' => $jira_settings->get('REST_URL'),
      '#disabled' => TRUE,
    ];
    $form['CREATE_ISSUE_URL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL for issue creations requests to Jira'),
      '#default_value' => $jira_settings->get('CREATE_ISSUE_URL'),
      '#disabled' => TRUE,
    ];
    $form['USERNAME'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User name authenticated with JIRA issue creation'),
      '#default_value' => $jira_settings->get('USERNAME'),
      '#disabled' => TRUE,
    ];
    $form['SERVER'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Server'),
      '#default_value' => $config->get('SERVER'),
    ];
    $form['DOMESTIC_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Domestic Project ID'),
      '#default_value' => $config->get('DOMESTIC_PROJECT'),
    ];
    $form['INTERNATIONAL_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('International Project ID'),
      '#default_value' => $config->get('INTERNATIONAL_PROJECT'),
    ];
    $form['VOUCHERS_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Vouchers Project ID'),
      '#default_value' => $config->get('VOUCHERS_PROJECT'),
    ];
    $form['REGION8_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Region 8 Project ID'),
      '#default_value' => $config->get('REGION8_PROJECT'),
    ];
    $form['REGION9_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Region 9 Project ID'),
      '#default_value' => $config->get('REGION9_PROJECT'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('webform_custom_submissions.form')
      ->set('SERVER', $form_state->getValue('SERVER'))
      ->set('DOMESTIC_PROJECT', $form_state->getValue('DOMESTIC_PROJECT'))
      ->set('INTERNATIONAL_PROJECT', $form_state->getValue('INTERNATIONAL_PROJECT'))
      ->set('VOUCHERS_PROJECT', $form_state->getValue('VOUCHERS_PROJECT'))
      ->set('REGION8_PROJECT', $form_state->getValue('REGION8_PROJECT'))
      ->set('REGION9_PROJECT', $form_state->getValue('REGION9_PROJECT'))
      ->save();
  }
}
