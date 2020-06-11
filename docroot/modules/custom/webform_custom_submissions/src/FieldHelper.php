<?php


namespace Drupal\webform_custom_submissions;

use Drupal\webform\webformSubmissionInterface;

// Used to declutter the handler service by providing mappings here
class FieldHelper {
  private $form_data = [];
  private $webform_id;
  private $jira_data = [];

  private $form_to_jira_mapping = [
    'your_information' =>
      [
        'your_name' => 'customfield_10090',
        'your_email' => 'customfield_10092',
        'your_phone' => 'customfield_10091',
      ],
    'verify_your_name' => 'customfield_10493',
    'your_office_center' => 'customfield_10093',
    'ccte_division_traveler' => 'customfield_10501',
    'cemm_division_traveler' => 'customfield_10501',
    'ceser_division_traveler' => 'customfield_10501',
    'cphea_division_traveler' => 'customfield_10501',
    'orm_division_traveker' => 'customfield_10501',
    'osape_division_traveler' => 'customfield_10501',
    'osim_division_traveler' => 'customfield_10501',
    'traveler_information' =>
      [
        'traveler_name' => 'customfield_10331',
        'traveler_email' => 'customfield_10620',
        'traveler_phone' => 'customfield_11423',
      ],
    'verify_traveler_s_name' => 'customfield_10493',
    'traveler_s_office_center' => 'customfield_10093',
    'office_point_of_contact' => 'customfield_10340',
    'traveler_type' => 'customfield_10190',
    'travel_type' => 'customfield_10191',
    'passport_expiration_date' => 'customfield_10492',
    'emergency_contact_information' => 'customfield_10356',
    'traveler_s_grade' => 'customfield_11821',
    'title_position' => 'customfield_11822',
    'is_personal_annual_leave_being_requested_' => 'customfield_10098',
    'dates_of_approved_leave' => 'customfield_10099',
    'do_you_already_have_a_room_reserved_' => 'customfield_10112',
    'mode_s_of_transportation' => 'customfield_10103',
    'have_you_made_your_own_airline_reservation_' => 'customfield_10435',
    'would_you_like_to_request_a_specific_flight_' => '',
    'information_about_specific_flight' => '',
    'additional_information_transportation' => 'customfield_10111',
    'travel_purpose' => 'customfield_10922',
    'is_this_travel_for_an_epa_conference_' => 'customfield_10426',
    'please_provide_the_conference_code' => 'customfield_10921',
    'travel_description' => 'customfield_10094',
    'how_will_this_trip_benefit_the_epa_' => 'customfield_10198',
    'has_the_traveler_taken_hstos_training_' => 'customfield_11827',
    'who_is_your_deputy_ethics_official_' => 'customfield_11823',
    'sponsoring_organization' => 'customfield_11825',
    'division_director_and_or_branch_chief_s_name' => 'customfield_10430',
    'traveler_s_qualifications' => 'customfield_10195',
    'destination_country_contact_s_name' => 'customfield_10357',
    'destination_country_contact_s_telephone' => 'customfield_10357',
    'destination_country_contact_s_org' => 'customfield_10357',
    'hosting_organization_point_of_contact' => 'customfield_10357',
    'is_someone_else_paying_for_a_portion_or_all_of_the_travel_' => 'customfield_10199',
    'who_is_paying_' => 'customfield_11424',
    'epa_office_paying' => 'customfield_11425',
    'cross_funding_label_' => 'customfield_10491',
    'agency_name' => 'customfield_11426',
    'name_of_funding_organization' => 'customfield_11427',
    'has_an_ethics_form_been_prepared_' => 'customfield_11428',
    'has_an_ethics_form_been_approved_' => 'customfield_10193',
    'will_training_dollars_be_used_for_this_travel_' => 'customfield_10246',
    'are_others_from_your_l_c_o_attending_this_meeting_as_well_' => 'customfield_10431',
    'please_provide_the_names_of_the_others_attending_this_meeting_wi' => 'customfield_10432',
    'transportation_expenses' => 'customfield_10278',
    'cost_of_taxi_uber_lyft' => 'customfield_10413',
    'cost_of_metro_bus_public_transportation' => 'customfield_10414',
    'cost_of_pov' => 'customfield_11431',
    'cost_of_rental_car_fee' => 'customfield_10384',
    'cost_of_rental_car_fuel' => 'customfield_10385',
    'cost_of_tolls' => 'customfield_10387',
    'cost_of_rail' => 'customfield_10383',
    'additional_expenses' => 'customfield_10415',
    'cost_of_hotel' => 'customfield_10375',
    'cost_of_internet_fees' => 'customfield_10377',
    'cost_of_phone_calls' => 'customfield_10416',
    'cost_of_airport_parking' => 'customfield_10412',
    'cost_of_baggage_fees' => 'customfield_10419',
    'cost_of_atm_fees' => 'customfield_10418',
    'cost_of_supplies' => 'customfield_10417',
    'cost_of_other_expenses' => 'customfield_10380',
    'additional_info_text' => 'customfield_10141',
    'attachment_s_' => 'file',
    'travel_request_number' => 'customfield_10208',
    'destination' => 'customfield_10303',
    'departure_date' => 'customfield_10095',
    'requested_change' => 'customfield_10209',
    'please_state_your_requested_concur_update_' => 'customfield_10209',
    'explanation' => 'customfield_10290',
    'please_enter_your_date_of_birth' => 'customfield_11429',
    'please_enter_the_traveler_s_date_of_birth' => 'customfield_11429',
    'emergency_contact_information_new' => 'customfield_11430',
    'preferred_home_airport_new' => 'customfield_10266',
    'have_a_frequent_flyer_account_with_an_airline_that_you_would_lik' => 'customfield_10354',
    'smoking_preference' => 'customfield_10120',
    'any_additional_requests_' => 'customfield_10291',
    'bed_size_preference' => 'customfield_10262',
    'hotel_preference' => 'customfield_10273',
    'flight_seating_preference' => 'customfield_10263',
    'please_state_you_question_or_comment_in_the_filed_below_' => 'customfield_10390',
    'receipts' => 'file',
    'required_receipts' => 'file',
    'daily_itinerary' => 'file',
    'please_attach_the_approved_sf_182' => 'file',
    'comments_on_travel_experience' => 'customfield_10308',
    'were_all_meals_provided_through_your_registration_costs_or_by_th' => 'customfield_10151',
    'do_you_have_any_other_expense_you_would_like_to_reimburse_to_you' => 'customfield_11420',
    'return_date' => 'customfield_10096',
    'returning_time' => 'customfield_10342',
    'departing_date' => 'customfield_10095',
    'departing_time' => 'customfield_10343',
    'round_trip_flight' => [
      'flying_from' => 'customfield_10104',
      'flying_to' => 'customfield_10105',
      'departing_date' => 'customfield_10095',
      'departing_time' => 'customfield_10343',
      'returning_date' => 'customfield_10096',
      'returning_time' => 'customfield_10342',
      'comments' => 'customfield_11921',
    ],
    'one_way_flight' => [
      'flying_from' => 'customfield_10104',
      'flying_to' => 'customfield_10105',
      'departing_date' => 'customfield_10095',
      'departing_time' => 'customfield_10343',
      'returning_date' => 'customfield_10096',
      'returning_time' => 'customfield_10342',
      'comments' => 'customfield_11921',
    ],
    'multi_city_flight' => 'customfield_11926',
    'reservation_information' => [
      "multi" => true,
      "hotel_name" => "customfield_10281",
      "hotel_location" => "customfield_11923",
      "hotel_phone" => "customfield_11922",
      "room_rate" => "customfield_10370"
    ],
    'please_provide_more_information_about_the_current_status_of_the_' => 'customfield_11924',
    'provided_meals' => "customfield_11925"
  ];

  public function is_checkbox_field($key) {
    return ($key == 'customfield_10411' ||
      $key == 'customfield_10415' ||
      $key == 'customfield_10150' ||
      $key == 'customfield_10149' ||
      $key == 'customfield_10148' ||
      $key == 'customfield_10151' ||
      $key == 'customfield_10278' ||
      $key == 'customfield_10280' ||
      $key == 'customfield_10103');
  }


  public function get_dropdown_fields() {
    return array(
      'customfield_10093',
      'customfield_10098',
      'customfield_10112',
      'customfield_10120',
      'customfield_10140',
      'customfield_10190',
      'customfield_10191',
      'customfield_10193',
      'customfield_10199',
      'customfield_10246',
      'customfield_10262',
      'customfield_10263',
      'customfield_10269',
      'customfield_10277',
      'customfield_10294',
      'customfield_10297',
      'customfield_10302',
      'customfield_10304',
      'customfield_10315',
      'customfield_10316',
      'customfield_10318',
      'customfield_10320',
      'customfield_10322',
      'customfield_10324',
      'customfield_10423',
      'customfield_10424',
      'customfield_10426',
      'customfield_10427',
      'customfield_10428',
      'customfield_10431',
      'customfield_10435',
      'customfield_10501',
      'customfield_10922',
      'customfield_11424',
      'customfield_11428',
      'customfield_11826',
      'customfield_11827',
    );
  }


  function isInternational() {
    return strpos($this->jira_data['customfield_10191'], 'International') !== false;
  }

  function isVoucher() {
    return strpos($this->webform_id, 'voucher') !== false;
  }

  public function getJiraData() {
    return $this->jira_data;
  }

  public function prepareFormData(WebformSubmissionInterface $webform_submission) {
    $this->form_data = $webform_submission->getData();
    $this->webform_id = $webform_submission->getWebform()->getOriginalId();
  }

  public function setProjectID($project_id) {
    $this->jira_data['fields']['project'] = ['id' => $project_id];
  }

  public function setIssueType() {
    $mapping = [
      'travel_amendment' => '32',
      'travel_cancellation' => '33',
      'travel_profile' => '34',
      'travel_concur_routing' => '10100',
      'travel_question' => '37',
      'travel_authorization' => '23',
      'travel_voucher' => '24',
      'travel_id_information' => '35',
    ];

    $this->jira_data['fields']['issuetype'] = ['id' => $mapping[$this->webform_id]];
  }

  public function isComposite($field_name) {
    $composite_fields = [
      'dates_of_approved_leave', 'emergency_contact_information', 'hotel_preference', 'reservation_information', 'provided_meals', 'multi_city_flight'
    ];
    return in_array($field_name, $composite_fields);
  }

  public function prepareJiraData() {
    $formatted_data = ['files' => [],];
    // Filter out values not included in Jira Type
    foreach ($this->form_data as $key => $value) {
      $jira_mapping = $this->form_to_jira_mapping[$key];
      if ($key === 'multi_city_flight') {
        $formatted_data = $this->parseMultiFlightField($value, $formatted_data);
      } else if ($this->isMulti($jira_mapping)) {
        $this->parseMultiFields($value, $jira_mapping, $formatted_data);
      } else if ($this->isComposite($key)) {
        $formatted_data[$jira_mapping] = $this->parseCompositeIntoSingleField($this->form_data[$key]);
      } else if ($jira_mapping === 'file') {
        $this->setFileIds($value, $formatted_data);
      } else if ($this->is_checkbox_field($jira_mapping)) {
        $formatted_data[$jira_mapping] = $value;
      } else {
        if (isset($jira_mapping) && !empty($value)) {
          if (is_array($value)) {
            $formatted_data = $this->handleFieldSet($value, $jira_mapping, $formatted_data);
          } else {
            $formatted_data[$jira_mapping] = $value;
          }
        }
      }
    }
    $this->jira_data = $formatted_data;
  }

  function print_missing_field($field_id) {
    echo $field_id . '<br />';
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
   * @param array $formatted_data
   * @return array
   */
  public function setFileIds($value, array &$formatted_data) {
    if (is_numeric($value)) {
      $formatted_data['files'][] = $value;
    } else if (is_array($value)) {
      foreach ($value as $file) {
        $formatted_data['files'][] = $file;
      }
    }
  }

  /**
   * @param array $value
   * @param $jira_mapping
   * @param array $allowed_custom_fields
   * @param array $formatted_data
   * @param $key
   * @return array
   */
  public function handleFieldSet(array $value, $jira_mapping, array $formatted_data): array {
    foreach ($value as $field_name => $field_value) {
      if (!empty($field_value)) {
        $custom_field_id = $jira_mapping[$field_name];
        $formatted_data[$custom_field_id] = $field_value;
      }
    }
    return $formatted_data;
  }

  /**
   * @param $jira_mapping
   * @return bool
   */
  public function isMulti($jira_mapping): bool {
    return isset($jira_mapping['multi']);
  }

  /**
   * @param $value
   * @param $jira_mapping
   * @param array $formatted_data
   * @return mixed
   */
  public function parseMultiFields($value, $jira_mapping, array &$formatted_data) {
    foreach ($value as $set_of_fields) {
      foreach ($set_of_fields as $field_name => $field_value) {
        if (isset($jira_mapping[$field_name])) {
          $formatted_data[$jira_mapping[$field_name]] .= $field_value . "\n";
        }
      }
    }
  }

  /**
   * @param $value
   * @param array $formatted_data
   * @param $jira_mapping
   * @param string $key
   * @return array
   */
  public function parseMultiFlightField($value, array &$formatted_data): array {
    $length_of_flights = count($value) - 1;
    $formatted_data[$this->form_to_jira_mapping['returning_time']] = $value[$length_of_flights]['departing_time'];
    $formatted_data[$this->form_to_jira_mapping['return_date']] = $value[$length_of_flights]['departing_date'];
    $formatted_data[$this->form_to_jira_mapping['departing_time']] = $value[0]['departing_time'];
    $formatted_data[$this->form_to_jira_mapping['departure_date']] = $value[0]['departing_date'];
    $formatted_data[$this->form_to_jira_mapping['multi_city_flight']] = $this->parseCompositeIntoSingleField($this->form_data['multi_city_flight']);
    return $formatted_data;
  }

}
