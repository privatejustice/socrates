<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'chatbot.civix.php';
use Socrates\Chat\ExtensionUtil as E;

/**
 * Implements hook_socrates_config().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function chatbot_socrates_config(&$config) {
  if (isset(Civi::$statics[__FUNCTION__])) {
    return;
  }
  Civi::$statics[__FUNCTION__] = 1;
  _chatbot_civix_socrates_config($config);
  Civi::dispatcher()->addListener('hook_socrates_post', 'chatbot_post_sms',1000);
}

function chatbot_post_sms($event){
  if($event->entity=='Activity' && $event->object->activity_type_id == CRM_Core_PseudoConstant::getKey('CRM_Activity_Bao\Activity', 'activity_type_id', 'Inbound SMS')) {
    $activity = socrates_api3('Activity', 'getsingle', ['id' => $event->id]);
    $client = new GuzzleHttp\Client();
    try {

      $response = $client->request('POST', CRM_Utils_System::url('socrates/chat/webhook/civisms', null, true), [
        'body' => json_encode([
          'authentication_token' => socrates_api3('setting', 'getvalue', ['name' => 'chatbot_civisms_authentication_token']),
          'text' => $activity['details'],
          'contact_id' => $activity['source_contact_id']
        ])
      ]);
      echo (string) $response->getBody();

    } catch (\Exception $e) {

        echo $e->getResponse()->getBody()->getContents();
    }

  }
}

/**
 * Implements hook_socrates_xmlMenu().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function chatbot_socrates_xmlMenu(&$files) {
  _chatbot_civix_socrates_xmlMenu($files);
}

/**
 * Implements hook_socrates_install().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function chatbot_socrates_install() {
  _chatbot_civix_socrates_install();
}

/**
 * Implements hook_socrates_postInstall().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function chatbot_socrates_postInstall() {
  _chatbot_civix_socrates_postInstall();
  // Necessary since we are communicating with ourselves via public post requests
  // (since that is what Botman wants us to do)
  socrates_api3('setting', 'create', ['chatbot_civisms_authentication_token' => Socrates\Chat\Utils::generateToken()]);
}

/**
 * Implements hook_socrates_uninstall().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function chatbot_socrates_uninstall() {
  _chatbot_civix_socrates_uninstall();
}

/**
 * Implements hook_socrates_enable().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function chatbot_socrates_enable() {
  _chatbot_civix_socrates_enable();
}

/**
 * Implements hook_socrates_disable().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function chatbot_socrates_disable() {
  _chatbot_civix_socrates_disable();
}

/**
 * Implements hook_socrates_upgrade().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function chatbot_socrates_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _chatbot_civix_socrates_upgrade($op, $queue);
}

/**
 * Implements hook_socrates_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function chatbot_socrates_managed(&$entities) {
  _chatbot_civix_socrates_managed($entities);
}

/**
 * Implements hook_socrates_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in Socrates 4.4+.
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function chatbot_socrates_caseTypes(&$caseTypes) {
  _chatbot_civix_socrates_caseTypes($caseTypes);
}

/**
 * Implements hook_socrates_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in Socrates 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function chatbot_socrates_angularModules(&$angularModules) {
  _chatbot_civix_socrates_angularModules($angularModules);
}

/**
 * Implements hook_socrates_alterSettingsFolders().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function chatbot_socrates_alterSettingsFolders(&$metaDataFolders = NULL) {
  _chatbot_civix_socrates_alterSettingsFolders($metaDataFolders);
}


/**
 * Implements hook_socrates_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function chatbot_socrates_entityTypes(&$entityTypes) {
  _chatbot_civix_socrates_entityTypes($entityTypes);
}

/**
 * Implements hook_socrates_navigationMenu().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 **/
function chatbot_socrates_navigationMenu(&$menu) {
  foreach(Socrates\Chat\Navigation::getItems() as $item){
    _chatbot_civix_insert_navigation_menu($menu, $item['parent'], $item);
  }
  _chatbot_civix_navigationMenu($menu);
}

function chatbot_socrates_permission(&$permissions){

  $prefix = E::ts('Chatbot') . ': ';

  $permissions['access chatbot'] = [
    $prefix . E::ts('access chatbot'),
    E::ts('Provides access to chatbot')
  ];
}

function chatbot_socrates_summaryActions(&$actions, $contactId){

  // If the contact has a mobile phone, start a conversation with them
  $count = socrates_api3('ChatUser', 'getcount', ['contact_id' => $contactId]);
  if($count){
      $actions['chatbot'] = [
      'title' => 'Chat - start a conversation',
      'weight' => 999,
      'ref' => 'chatbot',
      'key' => 'chatbot',
      'href' => CRM_Utils_System::url('socrates/chat/start', "cid=$contactId"),
    ];
  }
}

function chatbot_socrates_searchTasks( $objectName, &$tasks ){
  if($objectName == 'contact'){
    $tasks[] = [
      'title' => 'Chat - start a conversation',
      'class' => 'Socrates\Chat\Form_StartMultiple'
    ];
  }
}

function chatbot_socrates_tabs ( &$tabs, $contactId ) {
  $tabs[] = array(
    'title'  => 'Chat',
    'id'     => 'chat',
    'class' => 'livePage',
    'url'    => CRM_Utils_System::url('socrates/contact/view/chat', "reset=1&cid={$contactId}"),
    'weight' => 50,
    'count'  => Socrates\Chat\Utils::getChatCount($contactId)
  );
}
