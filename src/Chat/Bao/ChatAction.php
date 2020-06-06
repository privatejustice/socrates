<?php
namespace Socrates\Chat\Bao;

use Socrates\Chat\ExtensionUtil as E;

class ChatAction extends \Socrates\Chat\Dao\ChatAction {

  static function findByTypeAndQuestion($type, $questionId) {

    $actions = new self;
    $actions->type = $type;
    $actions->question_id = $questionId;
    $actions->find();

    return $actions;

  }
}
