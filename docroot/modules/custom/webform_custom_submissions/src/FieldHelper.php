<?php


namespace Drupal\webform_custom_submissions;

use Drupal\webform\webformSubmissionInterface;

// Used to declutter the handler service by providing mappings here
class FieldHelper {
  private $form_data = [];
  private $webform_id;
  private $jira_data = ['files' => [],];
  private $webform_title;
  private $vouchers_project;
  private $international_project;
  private $domestic_project;
  private $region8_project;
  private $region9_project;
  private $trip_type;
  private $flying_out_the_country;
  private $traveler_name;
  private $traveler_name_if_filling_for_someone_else;
  private $jira_issue_types;
  private $checkbox_fields;
  private $dropdown_fields;
  private $time_dropdown_fields;

  private $form_to_jira_mapping;

  public function isCheckboxField($key) {
    return (in_array($key, $this->checkbox_fields));
  }


  public function getDropdownFields() {
    return $this->dropdown_fields;
  }

  public function getTimeDropdownFields() {
    return $this->time_dropdown_fields;
  }


  /**
   * Determine if form is International
   * @return bool
   */
  function isInternational() {
    $is_international = false;
    if (isset($this->jira_data[$this->trip_type])) {
      $is_international = strpos($this->jira_data[$this->trip_type], 'International') !== false;
    }
    return $is_international;
  }

  function isRegion9() {
    return strpos($this->webform_id, 'region9') !== false;
  }

  function isRegion8() {
    return strpos($this->webform_id, 'region8') !== false;
  }

  function isVoucher() {
    return strpos($this->webform_id, 'voucher') !== false;
  }

  public function getJiraData() {
    return $this->jira_data;
  }

  public function addSummary() {
    $name = '';
    // Traveler
    if (!empty($this->jira_data[$this->traveler_name])) {
      $name = $this->jira_data[$this->traveler_name];
    }
    // Traveler if filling for someone else
    if (!empty($this->jira_data[$this->traveler_name_if_filling_for_someone_else])) {
      $name = $this->jira_data[$this->traveler_name_if_filling_for_someone_else];
    }
    $this->jira_data['fields']['summary'] = $this->webform_title . ': ' . $name;
  }

  public function prepareCustomFieldMappings($custom_mapping_config) {
    $this->checkbox_fields = explode('|', $custom_mapping_config->get('checkbox_fields'));
    $this->dropdown_fields = explode('|', $custom_mapping_config->get('dropdown_fields'));
    $this->time_dropdown_fields = explode('|', $custom_mapping_config->get('time_dropdown_fields'));
    $this->form_to_jira_mapping = json_decode($custom_mapping_config->get('form_to_jira_mapping'), TRUE);
    $this->trip_type = $custom_mapping_config->get('trip_type');
    $this->flying_out_the_country = $custom_mapping_config->get('flying_out_the_country');
    $this->traveler_name= $custom_mapping_config->get('traveler_name');
    $this->traveler_name_if_filling_for_someone_else= $custom_mapping_config->get('traveler_name_if_filling_for_someone_else');
    $this->jira_issue_types = json_decode($custom_mapping_config->get('jira_issue_types'), TRUE);

  }

  public function prepareFormData($config, WebformSubmissionInterface $webform_submission) {
    $this->domestic_project = $config->get('DOMESTIC_PROJECT');
    $this->international_project = $config->get('INTERNATIONAL_PROJECT');
    $this->vouchers_project = $config->get('VOUCHERS_PROJECT');
    $this->region8_project = $config->get('REGION8_PROJECT');
    $this->region9_project = $config->get('REGION9_PROJECT');
    $this->form_data = $webform_submission->getData();
    $this->webform_id = $webform_submission->getWebform()->getOriginalId();
    $this->webform_title = $webform_submission->getWebform()->get('title');
  }

  public function setProjectID($project_id) {
    $this->jira_data['fields']['project'] = ['id' => $project_id];
  }

  public function setIssueType() {
    $webform_type = str_replace(["region9_", "region8_"], '', $this->webform_id);
    $this->jira_data['fields']['issuetype'] = ['id' => $this->jira_issue_types[$webform_type]];
  }

  public function isCompositeToMerge($field_name) {
    $composite_fields_to_merge_into_textarea = [
      'dates_of_approved_leave', 'emergency_contact_information', 'reservation_information', 'provided_meals',
      'emergency_contact_information_new', 'address_of_meeting_s_'
    ];
    return in_array($field_name, $composite_fields_to_merge_into_textarea);
  }

  public function prepareJiraData() {
    foreach ($this->form_data as $key => $value) {
      if (!empty($value)) {
        $this->mapDataToJiraFields($key, $value);
      }
    }
    $this->setFlyingOutOfCountry();
    $this->setProjectBasedOnType();
    $this->setIssueType();
    $this->addSummary();
  }

  function print_missing_field($field_id) {
    echo $field_id . '<br />';
  }

  private function setFlyingOutOfCountry() {
    if ($this->isAuthorizationOrVoucher()) {
      if ($this->isInternational()) {
        $this->jira_data[$this->flying_out_the_country] = 'Yes';
      } else {
        $this->jira_data[$this->flying_out_the_country] = 'No';
      }
    }
  }

  private function isAuthorizationOrVoucher() {
    return $this->webform_id == 'travel_authorization' || $this->webform_id == 'travel_voucher';
  }


  function parseCompositeIntoSingleField($composite_field) {
    $text = '';
    $length = count($composite_field);
    $i = 0;
    foreach ($composite_field as $key => $field) {
      if (is_array($field)) {
        $text .= $this->parseCompositeIntoSingleField($field) . "\n";
      } else {
        if (!is_numeric($key)) {
          $text .= ucwords(str_replace('_', ' ', $key)) . ': ';
        }
        $text .= $field;
        if ($i < $length - 1) {
          $text .= ", ";
        }
      }
      $i++;
    }
    return $text;
  }

  /**
   * @param $value
   */
  public function setFileIds($value) {
    if (is_numeric($value)) {
      $this->jira_data['files'][] = $value;
    } else if (is_array($value)) {
      foreach ($value as $file) {
        $this->jira_data['files'][] = $file;
      }
    }
  }

  /**
   * @param array $value
   * @param $jira_mapping
   */
  public function handleFieldSet(array $value, $jira_mapping) {
    foreach ($value as $field_name => $field_value) {
      if (!empty($field_value)) {
        if (is_array($jira_mapping)) {
          $custom_field_id = $jira_mapping[$field_name];
          $this->jira_data[$custom_field_id] = $field_value;
        } else {
          $this->jira_data[$jira_mapping] .= $field_name . "/n";
        }
      }
    }
  }

  /**
   * @param $jira_mapping
   * @return bool
   */
  public function isMultiFieldset($jira_mapping): bool {
    return isset($jira_mapping['multi_fieldset']);
  }

  /**
   * @param $array_of_fieldsets
   * @param $jira_mapping
   */
  public function addMultiFieldsets($array_of_fieldsets, $jira_mapping) {
    foreach ($array_of_fieldsets as $set_of_fields) {
      foreach ($set_of_fields as $field_name => $field_value) {
        if (isset($jira_mapping[$field_name])) {
          $this->jira_data[$jira_mapping[$field_name]] .= $field_value . "\n";
        }
      }
    }
  }

  public function addMultiFields($array_of_field_values, $jira_field_id) {
    foreach ($array_of_field_values as $field_value) {
      $this->jira_data[$jira_field_id] .= $field_value . "\n";
    }
  }

  /**
   * @param $value
   */
  public function addMultiFlightField($value) {
    $length_of_flights = count($value) - 1;
    $this->jira_data[$this->form_to_jira_mapping['returning_time']] = $value[$length_of_flights]['departing_time'];
    $this->jira_data[$this->form_to_jira_mapping['return_date']] = $value[$length_of_flights]['departing_date'];
    $this->jira_data[$this->form_to_jira_mapping['departing_time']] = $value[0]['departing_time'];
    $this->jira_data[$this->form_to_jira_mapping['departure_date']] = $value[0]['departing_date'];
    $this->jira_data[$this->form_to_jira_mapping['multi_city_flight']] = $this->parseCompositeIntoSingleField($this->form_data['multi_city_flight']);
  }

  /**
   * @param $key
   * @return bool
   */
  public function isMultiCityFlight($key): bool {
    return $key === 'multi_city_flight';
  }

  /**
   * @param $jira_mapping
   * @return bool
   */
  public function isFile($jira_mapping): bool {
    return $jira_mapping === 'file';
  }

  /**
   * @param $key
   * @param $value
   */
  private function mapDataToJiraFields($key, $value) {
    if (isset($this->form_to_jira_mapping[$key])) {
      $jira_mapping = $this->form_to_jira_mapping[$key];
      if (!empty($jira_mapping)) {
        if ($this->isMultiCityFlight($key)) {
          $this->addMultiFlightField($value);
        } else if ($this->isMultiFieldset($jira_mapping)) {
          $this->addMultiFieldsets($value, $jira_mapping);
        } else if ($this->isMulti($jira_mapping)) {
          $this->addMultiFields($value, $jira_mapping[$key]);
        } else if ($this->isCompositeToMerge($key)) {
          $this->jira_data[$jira_mapping] = $this->parseCompositeIntoSingleField($this->form_data[$key]);
        } else if ($this->isFile($jira_mapping)) {
          $this->setFileIds($value);
        } else if ($this->isCheckboxField($jira_mapping)) {
          $this->jira_data[$jira_mapping] = $value;
        } else {
          if (is_array($value)) {
            $this->handleFieldSet($value, $jira_mapping);
          } else {
            $this->jira_data[$jira_mapping] = $value;
          }
        }
      }
    }
  }

  private function isMulti($jira_mapping) {
    return isset($jira_mapping['multi']);
  }

  public function setProjectBasedOnType(): void {
    if ($this->isRegion9()) {
      $this->setProjectID($this->region9_project);
    } else if ($this->isRegion8()) {
      $this->setProjectID($this->region8_project);
    } else {
      if ($this->isInternational()) {
        $this->setProjectID($this->international_project);
      } else if ($this->isVoucher()) {
        $this->setProjectID($this->vouchers_project);
      } else {
        $this->setProjectID($this->domestic_project);
      }
    }
  }

}
