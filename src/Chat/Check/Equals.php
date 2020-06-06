<?php
namespace Socrates\Chat\Check;

class Equals extends \Socrates\Chat\Check {

  function __construct($params){
    $this->equals = $params['equals'];
  }

  function check(){
    if(strtolower($this->text) == strtolower($this->equals)){
      return true;
    }
    return false;
  }

  function summarise(){
    return "answer equals '{$this->equals}'";
  }
}
