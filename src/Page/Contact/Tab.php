<?php
namespace Socrates\Chat\Page\Contact;

use Socrates\Chat\ExtensionUtil as E;

class Tab extends  Socrates\Core_Page {

  public function run() {
    // Get contact Id
    $this->_contactId = Socrates\Utils_Request::retrieve('cid', 'Positive', $this, TRUE);
    $this->assign('contactId', $this->_contactId);
    Socrates\Utils_System::setTitle(E::ts('View conversations'));

    // check logged in url permission
    Socrates\Contact_Page_View::checkUserPermission($this);

    $this->ajaxResponse['tabCount'] = Socrates\Chat\Utils::getChatCount($this->_contactId);
    return parent::run();
  }
}
