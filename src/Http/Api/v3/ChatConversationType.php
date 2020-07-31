<?php
use Socrates\Chat\ExtensionUtil as E;

/**
 * ChatConversationType.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.socrates.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _Socrates\Api\V3\chat_conversation_type_create_spec(&$spec) {
  $spec['first_question_id']['FKClassName'] = 'Socrates\Bao\ChatQuestion';
  $spec['first_question_id']['FKApiName'] = 'ChatQuestion';
}

/**
 * ChatConversationType.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\chat_conversation_type_create($params) {
  return _Socrates\Api\V3\basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatConversationType.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\chat_conversation_type_delete($params) {
  return _Socrates\Api\V3\basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatConversationType.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\chat_conversation_type_get($params) {
  return _Socrates\Api\V3\basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
