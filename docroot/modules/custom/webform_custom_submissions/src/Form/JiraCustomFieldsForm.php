<?php

namespace Drupal\webform_custom_submissions\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class JiraCustomFieldsForm extends ConfigFormBase {

  /**
   * @return array
   */
  protected function getEditableConfigNames() {
    return ['webform_custom_submissions.jira_custom_fields'];
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'jira_custom_fields';
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
    $config = $this->config('webform_custom_submissions.jira_custom_fields');

    $form['trip_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('JIRA Trip Type Custom Field'),
      '#description' => $this->t('Used to determine if form submission is international or domestic'),
      '#default_value' => $config->get('trip_type'),
    ];
    $form['flying_out_the_country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Flying out the country custom field'),
      '#description' => $this->t('Automatically set based on Trip Type'),
      '#default_value' => $config->get('flying_out_the_country'),
    ];
    $form['traveler_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Traveler name custom field'),
      '#description' => $this->t('Traveler name, used to write JIRA Request Name'),
      '#default_value' => $config->get('traveler_name'),
    ];
    $form['traveler_name_if_filling_for_someone_else'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Traveler name (if filling for someone else) custom field'),
      '#description' => $this->t('Traveler name (if filling for someone else), used to write JIRA Request Name'),
      '#default_value' => $config->get('traveler_name_if_filling_for_someone_else'),
    ];
    $form['jira_issue_types'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Jira Issue Types'),
      '#description' => $this->t('JSON object mapping Drupal form machine names to JIRA issue type IDs'),
      '#default_value' => $config->get('jira_issue_types'),
    ];
    $form['form_to_jira_mapping'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Jira Custom Field Mapping'),
      '#description' => $this->t('JSON object mapping webform field key names to JIRA custom fields'),
      '#default_value' => $config->get('form_to_jira_mapping'),
    ];
    $form['dropdown_fields'] = [
      '#type' => 'textarea',
      '#title' => $this->t('JIRA Dropdown fields'),
      '#description' => $this->t('Line separated list of JIRA dropdown custom fields'),
      '#default_value' => $config->get('dropdown_fields'),
    ];
    $form['time_dropdown_fields'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Time Dropdown Fields'),
      '#description' => $this->t('Line separated list of Time type dropdown fields. If these values are "none", no value is sent to JIRA.'),
      '#default_value' => $config->get('time_dropdown_fields'),
    ];
    $form['checkbox_fields'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Jira fields that are checkboxes'),
      '#default_value' => $config->get('checkbox_fields'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('webform_custom_submissions.jira_custom_fields')
      ->set('checkbox_fields', $form_state->getValue('checkbox_fields'))
      ->set('trip_type', $form_state->getValue('trip_type'))
      ->set('flying_out_the_country', $form_state->getValue('flying_out_the_country'))
      ->set('traveler_name', $form_state->getValue('traveler_name'))
      ->set('traveler_name_if_filling_for_someone_else', $form_state->getValue('traveler_name_if_filling_for_someone_else'))
      ->set('form_to_jira_mapping', $form_state->getValue('form_to_jira_mapping'))
      ->set('dropdown_fields', $form_state->getValue('dropdown_fields'))
      ->set('time_dropdown_fields', $form_state->getValue('time_dropdown_fields'))
      ->set('jira_issue_types', $form_state->getValue('jira_issue_types'))
      ->save();
  }
}
