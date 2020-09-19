<?php
namespace Socrates\Chat\Form;

use Api;

class StartMultiple extends Socrates\Contact_Form_Task
{

    function preProcess()
    {
        parent::preProcess();
        Socrates\Utils_System::setTitle(ts('Start a conversation'));

        $userParams = ['contact_id' => ["IN" => $this->_contactIds]];
        $countUsers = Api::render('ChatUser', 'getcount', $userParams);
        $userParams['option.limit'] = $countUsers;
        $users = Api::render('ChatUser', 'get', $userParams)['values'];

        foreach($users as $user){
            $this->serviceUsers[$user['service']][] = $user['contact_id'];
            if(!isset($serviceCount[$user['service']])) {
                $services[$user['service']] = $user['service'];
            }
        }

        $this->assign('serviceUsers', $this->serviceUsers);

        $element = $this->add(
            'select',
            'service',
            'Service',
            $services,
            true,
            ['class' => 'form-control crm-form-select']
        );

        $element = $this->addEntityRef(
            'conversation_type_id',
            'Conversation type',
            [
            'entity' => 'ChatConversationType',
            'class' => 'form-control crm-form-select'
            ],
            true
        );

        $this->assign('fields', ['conversation_type_id', 'service']);
    }

    public function postProcess()
    {

        $values = $this->exportValues();
        $session = \Socrates\Core_Session::singleton();

        foreach($this->serviceUsers[$values['service']] as $contactId){
            $params = [
            'id' => $contactId,
            'service' => $values['service'],
            'conversation_type_id' => $values['conversation_type_id']
            ];
            $result = Api::render('Contact', 'start_conversation', $params);
        }
        Socrates\Core_Session::setStatus(ts('Chat started with %1 contacts', [1 => count($this->serviceUsers[$values['service']])]));
        parent::postProcess();
    }
}
