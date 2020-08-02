<?php
namespace Socrates\Chat\Page;

use Socrates\Chat\ExtensionUtil as E;
use Api;

class ConversationType extends Socrates\Core_Page {

  public function run() {

    // TODO Implement paging so we can display more that 25 conversation types :)

  $this->assign('conversationTypes', Api::render('ChatConversationType', 'get')['values']);

  parent::run();

  }

}
