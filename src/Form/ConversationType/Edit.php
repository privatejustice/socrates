<?php
namespace Socrates\Chat\Form\ConversationType;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Edit extends \Socrates\Chat\Form\Good
{

    function getGoodTitle()
    {
        return 'Edit Conversation type';
    }

    var $fields = [
    1 => [
      'entity' => 'ChatConversationType',
      'field' => 'name',
      'title' => 'Name',
      'required' => true,
      'help' => 'A descriptive name for the conversation type',
    ],
    2 => [
      'entity' => 'ChatConversationType',
      'field' => 'first_question_id',
      'title' => 'First question',
      'required' => true,
      'help' => 'The opening question of this conversation type',
    ],
    3 => [
      'entity' => 'ChatConversationType',
      'field' => 'timeout',
      'required' => true,
      'title' => 'Timeout',
      'help' => 'Time in minutes, after which this conversation type should be considered complete',
    ],
    ];

    var $entities = [
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
      'param' => 'id',
    ]
    ];

    var $submitText = 'Save';

    function preProcess()
    {

        parent::preProcess();

        $this->fields[2]['entityref_api'] = [
        'label_field' => 'text',
        'search_field' => 'text',
        'params' => [
        'conversation_type_id' => $this->entities['ChatConversationType']['before']['id']
        ]
        ];

    }

    function getDestination()
    {
        return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
    }

    function getGoodContext()
    {
        return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
    }

}
