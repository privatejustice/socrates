<?php
namespace Socrates\Chat\Check;

class Anything extends \Socrates\Chat\Check {

  function __construct(){
  }

  function check(){
    return true;
  }

  function summarise(){
    return "any answer";
  }
}
