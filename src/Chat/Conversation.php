<?php
namespace Socrates\Chat;

use BotMan\BotMan\Messages\Conversations\Conversation as ConversationBase;
use BotMan\BotMan\Messages\Incoming\Answer;
use Api;

class Conversation extends ConversationBase {

  public $contactId;

  public function __construct($conversationType, $contactId = null) {

    $this->conversationType = $conversationType;
    $this->contactId = $contactId;

  }

  public function run() {

    if(empty($this->contactId)){
      $this->contactId = $this->getBot()->getMessage()->getExtras('contact_id');
    }
    $this->askQuestion($this->conversationType->first_question_id);

  }

  protected function askQuestion($questionId) {

    $question = \Socrates\Bao\ChatQuestion::findById($questionId);

    $text = $this->tokenReplacement($question->text, $this->contactId);// TODO contact token replacement

    $this->ask($text, $this->action($questionId), ['contact_id' => $this->contactId]);
    return;

  }

  protected function action($questionId) {

    return function(Answer $answer) use ($questionId) {
      $this->end = true;
      $actions = [
        'group' => function($groupId){

          Api::render('GroupContact', 'create', [
            'contact_id' => $this->contactId,
            'group_id' => $groupId
          ]);

        },

        'field' => function($field, $value){

          Api::render('Contact', 'create', ['id' => $this->contactId, $field => $value]);

        },

        'say' => function($text){

          $this->say($text, ['contact_id' => $this->contactId]);

        },

        // 'conversation' => function($conversationTypeId){
        //
        //   $conversationType = \Socrates\Bao\ChatConversationType::findById($conversationTypeId);
        //   $this->end = false;
        //   $this->bot->startConversation(new \Socrates\Chat\Conversation($conversationType));
        //
        // },

        'next' => function($questionId){

          $question = \Socrates\Bao\ChatQuestion::findById($questionId);
          $this->end = false;
          $this->askQuestion($questionId);

        },
      ];

      foreach($actions as $type => $closure) {
        $this->processAction($type, $answer->getText(), $questionId, $closure);
      }
      if($this->end){

        Api::render('Activity', 'create', [
          'id' => \Socrates\Chat\Utils::getOngoingConversation($this->contactId)['id'],
          'activity_status_id' => 'Completed'
        ]);
      }
    };
  }

  protected function processAction($type, $text, $questionId, $closure) {

    $action = \Socrates\Bao\ChatAction::findByTypeAndQuestion($type, $questionId);

    while($action->fetch()){
      $check = unserialize($action->check_object);
      if($check->matches($text)){

        // TODO add weight to 'next' actions so that they are executed in order

        $closure($action->action_data, $check->getMatch());
        if($type == 'next' || $type == 'conversation') {
          return;
        }

      }

    }

  }

  protected function tokenReplacement($text, $contactId) {

    //TODO token replacement

    return $text;

  }

}
