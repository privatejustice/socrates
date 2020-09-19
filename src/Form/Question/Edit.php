<?php
namespace Socrates\Chat\Form\Question;

use Socrates\Chat\ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Edit extends \Socrates\Chat\Form\Good
{

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
      'param' => 'id',
    ]
    ];

    var $submitText = 'Save';

    function getDelete()
    {
        return [
        'path' => 'socrates/chat/question/delete',
        'query' => 'id='.$this->entities['ChatQuestion']['before']['id']
        ];
    }

    function getGoodTitle()
    {
        return E::ts('Edit question: ').$this->entities['ChatQuestion']['before']['text'];
    }

    function getDestination()
    {
        return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
    }

    function getGoodContext()
    {
        return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
    }

}
