<?php
use Socrates\Chat\ExtensionUtil as E;

/**
 * ChatAction.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param  array $spec description of fields supported by this API call
 * @return void
 * @see    http://wiki.socrates.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _Socrates\Api\V3\ChatAction::create_spec(&$spec)
{
    // $spec['some_parameter']['api.required'] = 1;
}

/**
 * ChatAction.create API
 *
 * @param  array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\ChatAction::create($params)
{
    return _Socrates\Api\V3\basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatAction.delete API
 *
 * @param  array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\ChatAction::delete($params)
{
    return _Socrates\Api\V3\basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChatAction.get API
 *
 * @param  array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function Socrates\Api\V3\ChatAction::get($params)
{
    return _Socrates\Api\V3\basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
