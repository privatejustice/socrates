<?php
namespace Socrates\Chat\Form\ConversationType;

use Socrates\Chat\ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class Delete extends \Socrates\Chat\Form\Good_Delete
{

    var $entities = [
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
      'param' => 'id',
    ]
    ];

    var $deleteEntityText = 'conversation type';
    var $deleteEntityLabelField = 'name';

    function getGoodTitle()
    {
        return 'Delete conversation type';
    }

    function getGoodContext()
    {
        return Socrates\Utils_System::url('socrates/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
    }

    function getDestination()
    {
        return Socrates\Utils_System::url('socrates/chat/conversationType');
    }
}
