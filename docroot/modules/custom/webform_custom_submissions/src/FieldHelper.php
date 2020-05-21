<?php


namespace Drupal\webform_custom_submissions;

use Drupal\webform\webformSubmissionInterface;

// Used to declutter the handler service by providing mappings here
class FieldHelper {
  private $form_data = [];
  private $webform_id;
  private $jira_data = [];

  function isInternational() {
    return strpos($this->jira_data['customfield_10191'], 'International') >= 0;
  }

  function isVoucher() {
    return strpos($this->webform_id, 'voucher') >= 0;
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
      'travel_amendment_form'=> '32',
      'travel_cancellation' => '33',
      'travel_profile' => '34',
      'routing_change' => '10100',
      'travel_question' => '37',
      'travel_authorization' => '23',
      'travel_voucher' => '24',
      'traveler_id' => '35',
    ];

    $this->jira_data['fields']['issuetype'] = ['id' => $mapping[$this->webform_id]];
  }

  public function prepareJiraData() {
    $form_to_jira_mapping = [
      'your_information' =>
        [
          'your_name' => 'customfield_10090',
          'your_email' => 'customfield_10092',
          'your_phone' => 'customfield_10091',
        ],
      'verify_your_name' => 'customfield_10493',
      'your_office_center' => 'customfield_10093',
      'ccte_divisions' => 'customfield_10501',
      'cemm_divisions' => 'customfield_10501',
      'ceser_divisions' => 'customfield_10501',
      'cphea_divisions' => 'customfield_10501',
      'orm_division' => 'customfield_10501',
      'osape_division' => 'customfield_10501',
      'osim_division' => 'customfield_10501',
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
      'trip_type' => NULL,
      'one_way_flight' => NULL,
      'round_trip_flight' => NULL,
      'multi_city_flight' =>
        array(),
      'is_personal_annual_leave_being_requested_' => 'customfield_10098',
      'dates_of_approved_leave' => 'customfield_10099',
      'is_there_a_block_of_rooms_reserved_for_you_to_book_with_' => 'No',
      'do_you_already_have_a_room_reserved_' => 'customfield_10112',
      'reservation_information' => NULL,
      'please_stay_within_allowable_per_diem_if_selected_rate_is_over_p' => '',
      'do_you_have_a_hotel_chain_preference_' => 'No',
      'address_of_meeting_s_' => NULL,
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
      'daily_itinerary' => NULL,
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
      'please_attach_approved_ethics_form_' => NULL,
      'please_attach_your_invitational_letter_' => NULL,
      'will_training_dollars_be_used_for_this_travel_' => 'customfield_10246',
      'is_this_sf_182_approved_' => 'Yes',
      'please_provide_more_information_about_the_current_status_of_the_' => '',
      'please_attach_the_approved_sf_182' => NULL,
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
      'cost_of_conference_registration_fee' => NULL,
      'cost_of_hotel' => 'customfield_10375',
      'cost_of_hotel_parking' => '214',
      'cost_of_internet_fees' => 'customfield_10377',
      'cost_of_phone_calls' => 'customfield_10416',
      'cost_of_airport_parking' => 'customfield_10412',
      'cost_of_baggage_fees' => 'customfield_10419',
      'cost_of_cash_withdraw_finance_fees' => NULL,
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
      'airline_preference' => NULL,
      'please_state_you_question_or_comment_in_the_filed_below_' => 'customfield_10390',
      'receipts' => 'file',
      'required_receipts' => 'file',

    ];
    $formatted_data = [];
    foreach ($this->form_data as $key => $value) {
      if (isset($form_to_jira_mapping[$key])) {
        if (is_array($value)) {
          foreach ($form_to_jira_mapping[$key] as $nested_key => $nested_value) {
            $formatted_data[$nested_value] = $value[$nested_key];
          }
        } else {
          $formatted_data[$form_to_jira_mapping[$key]] = $value;
        }
      }
    }
    $this->jira_data = $formatted_data;
  }


  static function isFile($key) {
    // TODO: Filling out file logic for new file fields
    return $key == 'attachment_s_';
  }

}
