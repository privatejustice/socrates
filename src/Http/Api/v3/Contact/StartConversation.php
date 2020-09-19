<?php
use Socrates\Chat\ExtensionUtil as E;
use Api;


function Socrates\Api\V3\contact_start_conversation($params)
{

    // require fields
    $required = [
    'id',
    'service',
    'conversation_type_id'
    ];

    $missingFields = array_diff($required, array_keys($params));

    if(count($missingFields)) {
        throw new API_Exception('Mandatory key(s) missing from params array: ' . implode(', ', $missingFields));
    }

    // Check user has account with service
    try {
        $user = Api::render(
            'ChatUser', 'getsingle', [
            'service' => $params['service'],
            'contact_id' => $params['id']
            ]
        );
    } catch (\Exception $e) {
        throw new API_Exception("Could not find {$params['service']} user for contact_id {$params['id']}");
    }

    $conversationType = \Socrates\Bao\ChatConversationType::findById($params['conversation_type_id']);

    $conversationActivityParams = [
    'target_contact_id' => $params['id'],
    'activity_type_id' => 'Conversation',
    'activity_status_id' => 'Ongoing'
    ];

    $conversationActivityParams['subject'] = $conversationActivityParams['subject'] = $params['service'] . ': ' . Socrates\Chat\Utils::shorten($conversationType->name, 50);
    $conversationActivityParams['details'] = json_encode(
        [
        'service' => $params['service'],
        'conversation_type_id' => $params['conversation_type_id']
        ]
    );

    if(isset($params['source_contact_id'])) {
        $conversationActivityParams['source_contact_id'] = $params['source_contact_id'];
    }

    $ongoingConversationCount = Api::render('activity', 'getcount', $conversationActivityParams);

    if($ongoingConversationCount) {
        $conversationActivityParams['activity_status_id'] = 'Scheduled';
        $conversation = Api::render('activity', 'create', $conversationActivityParams);
        return Socrates\Api\V3\create_success();
    }else{
        $conversation = Api::render('activity', 'create', $conversationActivityParams);

        $botman = \Socrates\Chat\Botman::get($params['service']);
        $botman->middleware->sending(new \Socrates\Http\Middleware\Identify());
        $botman->middleware->sending(new \Socrates\Http\Middleware\RecordOutgoing());
        $botman->startConversation(
            new \Socrates\Chat\Conversation(
                $conversationType,
                $params['id']
            ),
            $user['user_id'],
            Socrates\Chat\Botman::getDriver($params['service'])
        );

        return Socrates\Api\V3\create_success();
    }
}
