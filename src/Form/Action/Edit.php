<?php
namespace Socrates\Chat\Form\Action;

use Socrates\Chat\ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Edit extends \Socrates\Chat\Form\Action_Add {

  function initEntities(){

    $this->entities = [
      'ChatAction' => [
        'type' => 'ChatAction',
        'param' => 'id',
        'references' => [
          'question_id' => [
            'entity' => 'ChatQuestion',
            'field' => 'id'
          ]
        ]
      ],
      'ChatQuestion' => [
        'type' => 'ChatQuestion',
        'process' => false
      ]
    ];
  }

  function getGoodTitle(){

    return E::ts('Edit action');

  }

  function getDelete() {
    return [
      'path' => 'socrates/chat/action/delete',
      'query' => 'id='.$this->entities['ChatAction']['before']['id']
    ];
  }

  function preProcessMassage(){

    parent::preProcessMassage();

    $check = unserialize($this->entities['ChatAction']['before']['check_object']);

    // Create serialized match object
    $this->entities['ChatAction']['before']['match'] = get_class($check);
    switch (get_class($check)) {
      case 'Socrates\Check_Contains':
        $this->entities['ChatAction']['before']['match_contains'] = $check->contains;
        break;

      case 'Socrates\Check_Equals':
        $this->entities['ChatAction']['before']['match_equals'] = $check->equals;
        break;
    }

    // Create action data
    switch ($this->entities['ChatAction']['before']['type']) {
      case 'next':
        $this->entities['ChatAction']['before']['next'] = $this->entities['ChatAction']['before']['action_data'];
        break;

      case 'say':
        $this->entities['ChatAction']['before']['say'] = $this->entities['ChatAction']['before']['action_data'];
        break;

      case 'conversation':
        $this->entities['ChatAction']['before']['conversation'] = $this->entities['ChatAction']['before']['action_data'];
        break;

      case 'group':
        $this->entities['ChatAction']['before']['group'] = $this->entities['ChatAction']['before']['action_data'];
        break;

      case 'field':
        $this->entities['ChatAction']['before']['field'] = $this->entities['ChatAction']['before']['action_data'];
        break;
    }
  }
}
