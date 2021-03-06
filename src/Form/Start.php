<?php
namespace Socrates\Chat\Form;

use Api;

class Start extends Good
{

    function getDestination()
    {
        return Socrates\Utils_System::url('socrates/contact/view', 'reset=1&cid='.$this->entities['Contact']['before']['id']);
    }

    function getGoodContext()
    {
        return Socrates\Utils_System::url('socrates/contact/view', 'reset=1&cid='.$this->entities['Contact']['before']['id']);
    }

    function getGoodTitle()
    {
        return 'Start a conversation with '.$this->entities['Contact']['before']['display_name'];
    }

    function initEntities()
    {
        $this->entities = [
        'Contact' => [
        'type' => 'Contact',
        'param' => 'cid',
        'process' => false
        ]
        ];
    }

    function initFields()
    {

        $users = Api::render('ChatUser', 'get', ['contact_id' => $this->entities['Contact']['before']['id']])['values'];

        $this->fields = [
        'Conversation:conversationTypeId' => [
        'entity' => 'Conversation',
        'field' => 'conversationTypeId',
        'title' => 'Conversation type',
        'type' => 'entityref',
        'required' => true,
        'entityref_entity' => 'ChatConversationType',
        ],
        'Conversation:ChatService' => [
        'entity' => 'Conversation',
        'required' => true,
        'field' => 'ChatService',
        'title' => 'Chat service',
        'type' => 'select',
        'options' => array_column($users, 'service', 'service')
        ]
        ];

    }

    public function postProcess()
    {

        $values = $this->exportValues();

        $session = \Socrates\Core_Session::singleton();

        $params = [
        'id' => $this->entities['Contact']['before']['id'],
        'service' => $values['Conversation:ChatService'],
        'conversation_type_id' => $values['Conversation:conversationTypeId']
        ];
        $result = Api::render('Contact', 'start_conversation', $params);
        Socrates\Core_Session::setStatus(ts('Chat started with %1', [1 => $this->entities['Contact']['before']['display_name']]));
        parent::postProcess();
    }
}
