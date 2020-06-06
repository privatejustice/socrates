<?php
namespace Socrates\Chat\Form\Question;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Add extends \Socrates\Chat\Form\Good {

  var $fields = [
    1 => [
      'entity' => 'ChatQuestion',
      'field' => 'text',
      'title' => 'Question',
      'required' => true,
      'help' => 'The text of the question',
    ]
  ];

  var $entities = [
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'references' => [
        'conversation_type_id' => [
          'entity' => 'ChatConversationType',
          'field' => 'id'
        ]
      ]
    ],
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
      'param' => 'conversationTypeId'
    ]
  ];

  var $submitText = 'Save';

  function getGoodTitle(){
    return 'Add question';
  }

  function getDestination() {
    return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

  function getGoodContext() {
    return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

}
