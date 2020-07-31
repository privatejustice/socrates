<?php
namespace Socrates\Bao;

use Socrates\Chat\ExtensionUtil as E;

class ChatHear extends \Socrates\Models\ChatHear {

  static function getActive(){
      $hears = new self;
      $hears->find();
      return $hears;
  }

}
