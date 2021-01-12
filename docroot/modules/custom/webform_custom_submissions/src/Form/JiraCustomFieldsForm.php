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
        $form['checkbox_fields'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Jira fields that are checkboxes'),
            '#default_value' => $config->get('checkbox_fields'),
        ];
        $form['flying_out_the_country'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Flying out the country custom field'),
            '#description' => $this->t('Used to determine if form submission is international or domestic'),
            '#default_value' => $config->get('flying_out_the_country'),
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

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        parent::submitForm($form, $form_state);
        $this->config('webform_custom_submissions.jira_custom_fields')
            ->set('checkbox_fields', $form_state->getValue('checkbox_fields'))
            ->set('flying_out_the_country', $form_state->getValue('flying_out_the_country'))
            ->set('form_to_jira_mapping', $form_state->getValue('form_to_jira_mapping'))
            ->set('dropdown_fields', $form_state->getValue('dropdown_fields'))
            ->save();
    }
}