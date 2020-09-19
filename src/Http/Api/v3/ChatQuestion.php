<?php
namespace Socrates\Api\V3;

use Socrates\Chat\ExtensionUtil as E;

class ChatQuestion
{
  
    /**
     * ChatQuestion.create API specification (optional)
     * This is used for documentation and validation.
     *
     * @param  array $spec description of fields supported by this API call
     * @return void
     * @see    http://wiki.socrates.org/confluence/display/CRMDOC/API+Architecture+Standards
     */
    static function create_spec(&$spec)
    {
        // $spec['some_parameter']['api.required'] = 1;
    }

    /**
     * ChatQuestion.create API
     *
     * @param  array $params
     * @return array API result descriptor
     * @throws API_Exception
     */
    static function create($params)
    {
        return _Socrates\Api\V3\basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
    }

    /**
     * ChatQuestion.delete API
     *
     * @param  array $params
     * @return array API result descriptor
     * @throws API_Exception
     */
    static function delete($params)
    {
        return _Socrates\Api\V3\basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
    }

    /**
     * ChatQuestion.get API
     *
     * @param  array $params
     * @return array API result descriptor
     * @throws API_Exception
     */
    static function get($params)
    {
        return _Socrates\Api\V3\basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
    }
}
