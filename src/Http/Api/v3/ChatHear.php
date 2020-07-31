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
function _Socrates\Api\V3\ChatHear::create_spec(&$spec) {
  // $spec['some_parameter']['api.required'] = 1;
}

/**
 * ChatHear.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\ChatHear::create($params) {
  return _Socrates\Api\V3\basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatHear.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\ChatHear::delete($params) {
  return _Socrates\Api\V3\basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatHear.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\ChatHear::get($params) {
  return _Socrates\Api\V3\basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
