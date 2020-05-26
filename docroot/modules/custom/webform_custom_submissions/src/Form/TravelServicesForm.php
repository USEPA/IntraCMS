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
    $form['DC_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('DC Project ID'),
      '#default_value' => $config->get('DC_PROJECT'),
    ];
    $form['CIN_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CIN Project ID'),
      '#default_value' => $config->get('CIN_PROJECT'),
    ];
    $form['RTP_PROJECT'] = [
      '#type' => 'textfield',
      '#title' => $this->t('RTP Project ID'),
      '#default_value' => $config->get('RTP_PROJECT'),
    ];
    $form['BASE_URL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Base Url for Jira'),
      '#default_value' => $config->get('BASE_URL'),
    ];
    $form['REST_URL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Url of REST requests to Jira'),
      '#default_value' => $config->get('REST_URL'),
    ];
    $form['CREATE_ISSUE_URL'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL for issue creations requests to Jira'),
      '#default_value' => $config->get('CREATE_ISSUE_URL'),
    ];
    $form['USERNAME'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User credentials in name:password format'),
      '#default_value' => $config->get('USERNAME'),
    ];
    $form['SERVER'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Server'),
      '#default_value' => $config->get('SERVER'),
    ];
    $form['SUBMIT_MESSAGE'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Submit Message'),
      '#default_value' => $config->get('SUBMIT_MESSAGE'),
    ];
    $form['OPTIONS'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Options'),
      '#default_value' => $config->get('OPTIONS'),
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
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('webform_custom_submissions.form')
      ->set('DC_PROJECT', $form_state->getValue('DC_PROJECT'))
      ->set('CIN_PROJECT', $form_state->getValue('CIN_PROJECT'))
      ->set('RTP_PROJECT', $form_state->getValue('RTP_PROJECT'))
      ->set('BASE_URL', $form_state->getValue('BASE_URL'))
      ->set('REST_URL', $form_state->getValue('REST_URL'))
      ->set('CREATE_ISSUE_URL', $form_state->getValue('CREATE_ISSUE_URL'))
      ->set('USERNAME', $form_state->getValue('USERNAME'))
      ->set('SERVER', $form_state->getValue('SERVER'))
      ->set('SUBMIT_MESSAGE', $form_state->getValue('SUBMIT_MESSAGE'))
      ->set('OPTIONS', $form_state->getValue('OPTIONS'))
      ->set('DOMESTIC_PROJECT', $form_state->getValue('DOMESTIC_PROJECT'))
      ->set('INTERNATIONAL_PROJECT', $form_state->getValue('INTERNATIONAL_PROJECT'))
      ->set('VOUCHERS_PROJECT', $form_state->getValue('VOUCHERS_PROJECT'))
      ->save();
  }
}
