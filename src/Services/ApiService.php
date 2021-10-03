<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Socrates\Services;

use Socrates\Models\ChatAction;
use Socrates\Models\ChatCache;
use Socrates\Models\ChatConversationType;
use Socrates\Models\ChatHear;
use Socrates\Models\ChatQuestion;
use Socrates\Models\ChatUser;
use Socrates\Models\Activity;

/**
 * 
 */
class ApiService
{

    protected $config;

    protected $models = [
        'ChatAction' => ChatAction::class,
        'ChatAction' => ChatAction::class,
        'ChatCache' => ChatCache::class,
        'ChatConversationType' => ChatConversationType::class,
        'ChatHear' => ChatHear::class,
        'ChatQuestion' => ChatQuestion::class,
        'ChatUser' => ChatUser::class,
        'Contact' => Contact::class,
        'Activity' => Activity::class,
        'activity' => Activity::class,
    ];

    public function __construct($config = false)
    {
        // if (!$this->config = $config) {
        //     $this->config = \Illuminate\Support\Facades\Config::get('generators.loader.models', []);
        // }
        // // $this->getModelServicesToArray(false);
    }

    /**
     * @return void
     */
    public function render($module, $action, $data)
    {
        if (isset($this->models[$module])) {
            $this->models[$module]::$action($data);
            return ;
        }

        dd('LinksPedreiro', $apiToken, $client, $data);
    }

    /**
    'activity', 'create', [
        'activity_type_id' => 'Incoming chat',
        'subject' => $subject,
        'details' => $details,
        'target_contact_id' => $contactId,
        'source_contact_id' => $contactId,
        'parent_id' => \Socrates\Chat\Utils::getOngoingConversation($contactId)['id']
      ]
      'ChatConversationType', 'create', $params
      'Contact', 'start_conversation', [
            'id' => $bot->getMessage()->getExtras('contact_id'),
            'source_contact_id' => $bot->getMessage()->getExtras('contact_id'),
            'service' => \Socrates\Chat\Driver::getServiceName($bot->getDriver()),
            'conversation_type_id' => \Socrates\Bao\ChatConversationType::findById($hear->chat_conversation_type_id)->id
          ]);

          Api::render('GroupContact', 'create', [
            'contact_id' => $this->contactId,
            'group_id' => $groupId
          ]);
          'Contact', 'create', ['id' => $this->contactId, $field => $value]);
          'Activity', 'create', [
          'id' => \Socrates\Chat\Utils::getOngoingConversation($this->contactId)['id'],
          'activity_status_id' => 'Completed'
        ]);
       */

}
