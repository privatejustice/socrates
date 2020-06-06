<?php
namespace Socrates\Chat\Check;

class Contains extends \Socrates\Chat\Check {

  function __construct($params){
    $this->contains = $params['contains'];
  }

  function check(){
    if(strpos(strtolower($this->text), strtolower($this->contains)) !== false) {
      return true;
    }
    return false;
  }

  function summarise(){
    return "answer contains '{$this->contains}'";
  }
}
