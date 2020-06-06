<?php
namespace Socrates\Chat\Form\Question;


use Socrates\Chat\ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Delete extends \Socrates\Chat\Form\Good_Delete {

  var $entities = [
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'param' => 'id',
    ]
  ];

  var $deleteEntityText = 'question';
  var $deleteEntityLabelField = 'text';

  function getGoodTitle(){
    return 'Delete question';
  }

  function getGoodContext() {
    return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }

  function getDestination() {
    return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }
}
