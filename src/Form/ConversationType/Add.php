<?php
namespace Socrates\Chat\Form\ConversationType;

use Socrates\Chat\ExtensionUtil as E;
use Api;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Add extends \Socrates\Chat\Form\Good {


  var $fields = [
    [
      'entity' => 'ChatConversationType',
      'field' => 'name',
      'title' => 'Conversation name',
      'required' => true,
      'help' => 'A descriptive name for the conversation',
    ],
    [
      'entity' => 'ChatQuestion',
      'field' => 'text',
      'title' => 'Opening question',
      'required' => true,
      'help' => 'The first question of this conversation',
    ],
    [
      'entity' => 'ChatConversationType',
      'field' => 'timeout',
      'title' => 'Timeout',
      'required' => true,
      'help' => 'Time in minutes, after which this conversation should be considered complete',
    ],
  ];

  var $entities = [
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
    ],
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'references' => [
        'conversation_type_id' => [
          'entity' => 'ChatConversationType',
          'field' => 'id'
          ]
      ]
    ]
  ];

  var $submitText = 'Add';


  function getGoodTitle(){
    return 'Add Conversation type';
  }

  function setDefaultValues(){

    $defaults['ChatConversationType:timeout'] = 30;
    return $defaults;

  }

  function getDestination() {
    return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['after']['id']);
  }

  function getGoodContext() {
    return Socrates\Utils_System::url('socrates/chat/conversationType');
  }


  function postProcess(){

    parent::postProcess();

    $params = [
      'id' => $this->entities['ChatConversationType']['after']['id'],
      'first_question_id' => $this->entities['ChatQuestion']['after']['id']
    ];

    $result = Api::render('ChatConversationType', 'create', $params);

  }

}
