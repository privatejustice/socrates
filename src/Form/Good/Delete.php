<?php
namespace Socrates\Chat\Form\Good;

use Socrates\Chat\ExtensionUtil as E;
use Api;

/**
 * Form controller class
 *
 * @see https://wiki.socrates.org/confluence/display/CRMDOC/QuickForm+Reference
 */
abstract class Delete extends \Socrates\Chat\Form\Good {

  var $entities = [];

  var $fields = [];

  var $submitText = 'Delete';

  function buildQuickForm() {
    $description = $this->getDescription();
    $this->addHelp('form', 'top', "Are you sure you want to delete the {$this->deleteEntityText} '{$description}'", 'warning');
    parent::buildQuickForm();
  }

  function getDescription(){
    return reset($this->entities)['before'][$this->deleteEntityLabelField];
  }

  function postProcess() {

    foreach($this->entities as &$entity) {

      if(isset($entity['process']) && $entity['process'] === false){
        continue;
      }

      $result = Api::render($entity['type'], 'delete', ['id' => $entity['before']['id']]);
    }

    $this->controller->_destination = $this->getDestination();

  }

}
