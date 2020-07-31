<?php
namespace Socrates\Chat\Page;

use Socrates\Chat\ExtensionUtil as E;

class ConversationType extends Socrates\Core_Page {

  public function run() {

    // TODO Implement paging so we can display more that 25 conversation types :)

  $this->assign('conversationTypes', socrates_api3('ChatConversationType', 'get')['values']);

  parent::run();

  }

}
