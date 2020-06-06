<?php
namespace Socrates\Chat\Bao;

use Socrates\Chat\ExtensionUtil as E;

class ChatHear extends \Socrates\Chat\Dao\ChatHear {

  static function getActive(){
      $hears = new self;
      $hears->find();
      return $hears;
  }

}
