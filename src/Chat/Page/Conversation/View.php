<?php
namespace Socrates\Chat\Page\Conversation;

use Socrates\Chat\ExtensionUtil as E;

class View extends Socrates\Core_Page {

  public function run() {

    $id = Socrates\Utils_Request::retrieve('id', 'Positive', $this);

    $conversation = socrates_api3('Activity', 'getsingle', ['id' => $id]);
    $chats = socrates_api3('Activity', 'get', [
      'parent_id' => $id,
      'activity_type_id' => ['IN' => ['Incoming chat', 'Outgoing chat']]
    ])['values'];

    Socrates\Utils_System::setTitle(E::ts('View conversation: ' . $conversation['subject']));

    $activityTypes = array_column(socrates_api3('OptionValue', 'get', [
      'option_group_id' => 'activity_type',
      'name' => ['IN' => ['Incoming chat', 'Outgoing chat']]
    ])['values'], 'label', 'value');

    $activityStatuses = array_column(socrates_api3('OptionValue', 'get', [ 'option_group_id' => 'activity_status', ])['values'], 'label', 'value');

    $activityTypeClasses = [
      'Incoming chat' => 'incoming',
      'Outgoing chat' => 'outgoing'
    ];

    foreach($chats as &$chat){
      $chat['class'] = $activityTypeClasses[$activityTypes[$chat['activity_type_id']]];
    }

    $sourceContact = socrates_api3('Contact', 'getsingle', array(
      'return' => array("display_name"),
      'id' => $conversation['source_contact_id'],
    ));
    $url = Socrates\Utils_System::url('socrates/contact/view', 'reset=1&cid='.$conversation['source_contact_id']);
    $conversation['source_contact'] = "<a href='{$url}'>{$sourceContact['display_name']}</a>";

    // Format Date
    $conversation['date'] = Socrates\Utils_Date::customFormat($conversation['activity_date_time']);
    $conversation['status'] = $activityStatuses[$conversation['status_id']];

    $this->assign('conversation', $conversation);
    $this->assign('chats', $chats);

    Socrates\Core_Resources::singleton()->addStyleFile('chatbot', 'css/chatbot.css');

    parent::run();

  }

}
