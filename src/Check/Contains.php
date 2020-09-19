<?php
namespace Socrates\Check;

class Contains extends \Socrates\Check
{

    function __construct($params)
    {
        $this->contains = $params['contains'];
    }

    function check()
    {
        if(strpos(strtolower($this->text), strtolower($this->contains)) !== false) {
            return true;
        }
        return false;
    }

    function summarise()
    {
        return "answer contains '{$this->contains}'";
    }
}
