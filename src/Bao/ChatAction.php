<?php
namespace Socrates\Bao;

use Socrates\Chat\ExtensionUtil as E;

class ChatAction extends \Socrates\Models\ChatAction
{

    static function findByTypeAndQuestion(string $type, $questionId): self
    {

        $actions = new self;
        $actions->type = $type;
        $actions->question_id = $questionId;
        $actions->find();

        return $actions;

    }
}
