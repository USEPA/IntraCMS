<?php

namespace Drupal\webform_custom_submissions;

use Drupal\webform\WebformSubmissionInterface;
use GuzzleHttp\Client;
use \Exception;
use Drupal\file\Entity\File;
use Psr\Http\Message\StreamInterface;

class JiraSubmissionHandler {

  protected $create_issue_url;
  protected $domestic_project;
  protected $international_project;
  protected $submission_client;
  protected $username;
  protected $vouchers_project;
  protected $field_helper;
  protected $travel_services_config;
  protected $password;

  public function __construct($config) {
    $this->travel_services_config = $config->get('webform_custom_submissions.form');
     $system_config = $config->get('system.passwords');
    $this->username = $this->travel_services_config->get('USERNAME');
    $this->password = $system_config->get('jira');
    $issue_creation_url = $this->travel_services_config->get('CREATE_ISSUE_URL');

    $this->create_issue_url = $this->travel_services_config->get('CREATE_ISSUE_URL');
    $this->domestic_project = $this->travel_services_config->get('DOMESTIC_PROJECT');
    $this->international_project = $this->travel_services_config->get('INTERNATIONAL_PROJECT');
    $this->submission_client = new Client(['base_uri' => $issue_creation_url]);
    $this->vouchers_project = $this->travel_services_config->get('VOUCHERS_PROJECT');
    $this->field_helper = new FieldHelper();
  }

  /*
   * Script for getting, formatting and submitting travel services form data to JIRA
   */

  public function submitToJira(WebformSubmissionInterface $webform_submission) {
    try {
      $fieldHelper = new FieldHelper();

      $fieldHelper->prepareFormData($this->travel_services_config, $webform_submission);
      $fieldHelper->prepareJiraData();
      $jira_data = $fieldHelper->getJiraData();
      $postData = $this->compilePOSTData($jira_data);
      $issueId = $this->createIssueAndReturnID($postData);
      if (isset($issueId)) {
        try {
          $this->attachFiles($issueId, $jira_data);
        } catch (Exception $e) {
          drupal_set_message(t('There was an error uploading your files to JIRA.'), 'error');
          \Drupal::logger('Travel Services File Upload Exception')->error($e->getMessage());
        }
      } else {
        drupal_set_message(t('There was an error processing your request. Code-0002'), 'error');
        \Drupal::logger('Travel Services Error')->error('Unidentified Error: JIRA Response did not return Issue ID');
      }
    } catch (Exception $e) {
      \Drupal::logger('Travel Services Exception')->error($e->getMessage());
      drupal_set_message(t('Unable to process request at this time, please try again later.'), 'error');
    }
  }

  //Compiles POST data and returns the data formatted as JSON
  protected function compilePOSTData($form_data) {

    $dropDowns = $this->field_helper->get_dropdown_fields();

    $data = array('fields' => array());

    //Add POST variables to the array
    foreach ($form_data as $key => $val) {
      // ignore file uploads, we are handling these later
      if ($key === 'files') {
        continue;
      }

      if ($key == 'customfield_10191') {
        $data['fields'][$key] = array('value' => $val);
      } elseif ($key == 'customfield_10093') {
        $data['fields'][$key] = array('value' => $val);
      } //Capture dropdowns and turn them into arrays
      elseif (in_array($key, $dropDowns)) {
        //ignore time dropdowns if no time is selected
        if (($key == 'customfield_10322' ||
            $key == 'customfield_10320' ||
            $key == 'customfield_10324' ||
            $key == 'customfield_10316' ||
            $key == 'customfield_10302' ||
            $key == 'customfield_10318') && $val == 'none'
        ) {
          //do nothing
        } else {
          $data['fields'][$key] = array('value' => $val);
        }
      } //Capture Checkboxes and turn them into arrays
      elseif ($this->field_helper->isCheckboxField($key)) {
        $checkboxArray = array();
        foreach ($val as $field2 => $value2) {
          array_push($checkboxArray, array('value' => $value2));
        }
        $data['fields'][$key] = $checkboxArray;
      }
      //Drupal 7 FAPI #states property does not currently support 'OR'
      //This workaround allows us to hide, show elements on 'OR' - These are fields in Travel Authorization
      elseif ($key == 'customfield_10431') {
        $data['fields']['customfield_10431'] = array('value' => $val);
      }
      //Drupal 7 FAPI #states property does not currently support 'OR'
      //This workaround allows us to hide, show elements on 'OR' - These are fields in Travel Authorization
      elseif ($key == 'customfield_10105a' ||
        $key == 'customfield_10105b' ||
        $key == 'customfield_10105c' && $val != ''
      ) {
        $data['fields']['customfield_10105'] = $val;
      } //Capture the rest of the customfield POST variables
      else {
        $data['fields'][$key] = $val;
      }
    }//end foreach

    if ($form_data['customfield_10431'] == 'Yes')
      $data['fields']['customfield_10431'] = array('value' => 'Yes');

    $data['fields']['project'] = $form_data['fields']['project'];
    $data['fields']['issuetype'] = $form_data['fields']['issuetype'];
    $data['fields']['summary'] = $form_data['fields']['summary'];
    unset($data['fields']['fields']);
    return $data;
  }

  /**
   * Builds and sends cURL request for the form POST data
   * @param $jsonData
   * @return StreamInterface|Exception Returns the body as a stream.
   *
   */
  protected function createIssueAndReturnID($jsonData) {
    $issue_id = null;
    try {
      \Drupal::logger('Travel Services Payload')->info('<pre><code>' . print_r($jsonData, TRUE) . '</code></pre>');
      $response = $this->submission_client->request('POST',
        $this->create_issue_url,
        ['json' => $jsonData, 'auth' => ["{$this->username}", "{$this->password}"]]);
      if ($response->getBody()) {
        $body = json_decode($response->getBody());
        if (isset($body->id)) {
          $issue_id = $body->id;
        }
        \Drupal::logger('Travel Services Response')->info('<pre><code>' . print_r($body, TRUE) . '</code></pre>');
      }
    } catch (Exception $e) {
      \Drupal::logger('Travel Services Response')->error($e->getMessage());
      drupal_set_message(t('There was an error processing your request. Code-0001'), 'error');
    }
    return $issue_id;
  }


  /**
   * @param $id
   * @param $form_data
   * @return array
   * @throws Exception
   */
  protected function attachFiles($id, $form_data) {
    $url = $this->create_issue_url . $id . '/attachments/';
    $fileNames = [];
    foreach ($form_data['files'] as $fid) {
      $fileData = ['size' => 0];
      $file = File::load($fid);
      if (is_object($file)) {
        $fileData = array(
          'tmp_name' => \Drupal::service('file_system')->realpath($file->getFileUri()),
          'name' => $file->getFilename(),
          'size' => intval($file->getSize()),
          'mime' => $file->getMimeType(),
        );
      }
      $payload = ['auth' => ["{$this->username}", "{$this->password}"],
        'X-Atlassian-Token' => "nocheck",
        'Content-Type' => 'multipart/form-data',
        'multipart' => [
          [
            'name' => 'file',
            'contents' => file_get_contents($fileData['tmp_name']),
            'filename' => $fileData['name'],
          ]
        ]
      ];

      if ($fileData['size'] > 0) {
        $response = $this->submission_client->post(
          $url,
          $payload
        );
        if ($response->getStatusCode() == 200) {
          $decodedResponse = $response->getBody();
          \Drupal::logger('Travel Services Response')->info('<pre><code>' . print_r($decodedResponse, TRUE) . '</code></pre>');
          $fileNames[] = $fileData['name'];
        } else {
          \Drupal::logger('Travel Services Response')->error('<pre><code>' . print_r($response->getBody(), TRUE) . '</code></pre>');
          throw new \Exception("File Upload error. Did not recieve 200 code response");
        }
      }
    }
    return $fileNames;

  }

}
