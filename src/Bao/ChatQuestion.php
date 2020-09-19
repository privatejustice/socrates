<?php
namespace Socrates\Bao;

use Socrates\Chat\ExtensionUtil as E;

class ChatQuestion extends \Socrates\Models\ChatQuestion
{

    /**
     * Create a new ChatQuestion based on array-data
     *
     * @param  array $params key-value pairs
     * @return Socrates\Models\ChatQuestion|NULL
     *
    public static function create($params) {
      $className = 'Socrates\Models\ChatQuestion';
      $entityName = 'ChatQuestion';
      $hook = empty($params['id']) ? 'create' : 'edit';
        Socrates\Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
      $instance = new $className();
      $instance->copyValues($params);
      $instance->save();
      Socrates\Utils_Hook::post($hook, $entityName, $instance->id, $instance);
        return $instance;
    } 
*/

}
