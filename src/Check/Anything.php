<?php
namespace Socrates\Check;

class Anything extends \Socrates\Check
{

    function __construct()
    {
    }

    function check()
    {
        return true;
    }

    function summarise()
    {
        return "any answer";
    }
}
