<?php
use Socrates\Chat\ExtensionUtil as E;

/**
 * ChatHear.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.socrates.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _socrates_api3_chat_hear_create_spec(&$spec) {
  // $spec['some_parameter']['api.required'] = 1;
}

/**
 * ChatHear.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function socrates_api3_chat_hear_create($params) {
  return _socrates_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatHear.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function socrates_api3_chat_hear_delete($params) {
  return _socrates_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatHear.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function socrates_api3_chat_hear_get($params) {
  return _socrates_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}