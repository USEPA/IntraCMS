<?php

namespace Drupal\webform_custom_submissions;

use Drupal\webform\WebformSubmissionInterface;
use GuzzleHttp\Client;
use \Exception;
use Drupal\file\Entity\File;
use GuzzleHttp\Exception\GuzzleException;

class JiraSubmissionHandler
{

  protected $create_issue_url;
  protected $domestic_project;
  protected $international_project;
  protected $submission_client;
  protected $username;
  protected $vouchers_project;
  protected $field_helper;
  protected $jira_services_config;
  protected $jira_custom_fields_config;
  protected $password;
  protected $submitted_ticket;
  protected $uploaded_file_names;
  protected $base_url;

  public function __construct($config)
  {
    $this->jira_services_config = $config->get('webform_custom_submissions.form');
    $this->jira_custom_fields_config = $config->get('webform_custom_submissions.jira_custom_fields');
    $jira_settings = \Drupal::config('webform_custom_submissions.form');
    $system_config = $config->get('system.passwords');
    $this->username = $jira_settings->get('USERNAME');
    $this->password = $system_config->get('jira');
    $issue_creation_url = $jira_settings->get('CREATE_ISSUE_URL');
    $this->create_issue_url = $issue_creation_url;
    $this->base_url = $jira_settings->get('BASE_URL');
    $this->domestic_project = $this->jira_services_config->get('DOMESTIC_PROJECT');
    $this->international_project = $this->jira_services_config->get('INTERNATIONAL_PROJECT');
    $this->submission_client = new Client(['base_uri' => $issue_creation_url]);
    $this->vouchers_project = $this->jira_services_config->get('VOUCHERS_PROJECT');

    $this->field_helper = new FieldHelper();
  }

  /**
   * Generate POST JSON and submit to JIRA API. Handle Exceptions if JIRA fails, or uploading files fail.
   * @param WebformSubmissionInterface $webform_submission
   * @throws Exception
   */
  public function submitToJira(WebformSubmissionInterface $webform_submission)
  {
    try {
      $this->field_helper->prepareCustomFieldMappings($this->jira_custom_fields_config);
      $this->field_helper->prepareFormData($this->jira_services_config, $webform_submission);
      $this->field_helper->prepareJiraData();
      $jira_data = $this->field_helper->getJiraData();
      $postData = $this->compilePOSTData($jira_data);
      $jira_api_response_body = $this->createIssueAndResponse($postData);

      if (isset($jira_api_response_body->id) && isset($jira_api_response_body->key)) {
        $this->submitted_ticket = $jira_api_response_body->key;
        try {
          $this->uploaded_file_names = $this->uploadFiles($jira_api_response_body->id, $jira_data);
        } catch (Exception $e) {
          \Drupal::logger('Travel Services File Upload Exception')->error($e->getMessage());
          throw new Exception('There was an error uploading your files to JIRA.');
        }
      } else {
        \Drupal::logger('Travel Services Error')->error('Unidentified Error: JIRA Response did not return Issue ID');
        throw new Exception('There was an error processing your request. Code-0002');
      }
    } catch (GuzzleException $ge) {
      \Drupal::logger('Travel Services Exception')->error($ge->getMessage());
      throw new Exception('There was an error processing your request. Code-0001');
    } catch (Exception $e) {
      \Drupal::logger('Travel Services Exception')->error($e->getMessage());
      throw new Exception('Unable to process request at this time, please try again later.');
    }
  }

  public function getSubmittedTicket()
  {
    return $this->submitted_ticket;
  }

  public function getSubmittedTicketURL()
  {
    return $this->base_url . '/browse/' . $this->submitted_ticket;
  }

  public function getUploadedFileNames()
  {
    return $this->uploaded_file_names;
  }

  //Compiles POST data and returns the data formatted as JSON
  protected function compilePOSTData($form_data)
  {

    $dropDowns = $this->field_helper->getDropdownFields();
    $timeDropDowns = $this->field_helper->getTimeDropdownFields();

    $data = array('fields' => array());

    //Add POST variables to the array
    foreach ($form_data as $key => $val) {
      // ignore file uploads, we are handling these later
      if ($key === 'files') {
        continue;
      }

      if (in_array($key, $dropDowns)) {
        //ignore time dropdowns if no time is selected
        if (!(in_array($key, $timeDropDowns) && $val == 'none')) {
          $data['fields'][$key] = array('value' => $val);
        }
      } //Capture Checkboxes and turn them into arrays
      elseif ($this->field_helper->isCheckboxField($key)) {
        $checkboxArray = array();
        foreach ($val as $field2 => $value2) {
          array_push($checkboxArray, array('value' => $value2));
        }
        $data['fields'][$key] = $checkboxArray;
      } else {
        $data['fields'][$key] = $val;
      }
    }

    $data['fields']['project'] = $form_data['fields']['project'];
    $data['fields']['issuetype'] = $form_data['fields']['issuetype'];
    $data['fields']['summary'] = $form_data['fields']['summary'];
    unset($data['fields']['fields']);
    return $data;
  }

  /**
   * Builds and sends cURL request for the form POST data
   * @param $jsonData
   * @return mixed|null
   * @throws Exception | GuzzleException
   */
  protected function createIssueAndResponse($jsonData)
  {
    $jira_api_response_body = null;
    try {
      \Drupal::logger('Travel Services Payload')->info('<pre><code>' . print_r($jsonData, TRUE) . '</code></pre>');
      $response = $this->submission_client->request('POST',
        $this->create_issue_url,
        ['json' => $jsonData, 'auth' => ["{$this->username}", "{$this->password}"]]);
      if ($response->getBody()) {
        $jira_api_response_body = json_decode($response->getBody());
        \Drupal::logger('Travel Services Response')->info('<pre><code>' . print_r($jira_api_response_body, TRUE) . '</code></pre>');
      }
    } catch (Exception $e) {
      \Drupal::logger('Travel Services Response')->error($e->getMessage());
      throw $e;
    }
    return $jira_api_response_body;
  }


  /**
   * @param $id
   * @param $form_data
   * @return array
   * @throws Exception
   */
  protected function uploadFiles($id, $form_data)
  {
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
