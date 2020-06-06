<?php

require_once 'chatbot_civirules.civix.php';
use CRM_ChatbotCivirules_ExtensionUtil as E;

/**
 * Implements hook_socrates_config().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function chatbot_civirules_socrates_config(&$config) {
  _chatbot_civirules_civix_socrates_config($config);
}

/**
 * Implements hook_socrates_xmlMenu().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function chatbot_civirules_socrates_xmlMenu(&$files) {
  _chatbot_civirules_civix_socrates_xmlMenu($files);
}

/**
 * Implements hook_socrates_install().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function chatbot_civirules_socrates_install() {
  _chatbot_civirules_civix_socrates_install();
}

/**
 * Implements hook_socrates_postInstall().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function chatbot_civirules_socrates_postInstall() {
  _chatbot_civirules_civix_socrates_postInstall();
}

/**
 * Implements hook_socrates_uninstall().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function chatbot_civirules_socrates_uninstall() {
  _chatbot_civirules_civix_socrates_uninstall();
}

/**
 * Implements hook_socrates_enable().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function chatbot_civirules_socrates_enable() {
  _chatbot_civirules_civix_socrates_enable();
}

/**
 * Implements hook_socrates_disable().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function chatbot_civirules_socrates_disable() {
  _chatbot_civirules_civix_socrates_disable();
}

/**
 * Implements hook_socrates_upgrade().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function chatbot_civirules_socrates_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _chatbot_civirules_civix_socrates_upgrade($op, $queue);
}

/**
 * Implements hook_socrates_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function chatbot_civirules_socrates_managed(&$entities) {
  _chatbot_civirules_civix_socrates_managed($entities);
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
function chatbot_civirules_socrates_caseTypes(&$caseTypes) {
  _chatbot_civirules_civix_socrates_caseTypes($caseTypes);
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
function chatbot_civirules_socrates_angularModules(&$angularModules) {
  _chatbot_civirules_civix_socrates_angularModules($angularModules);
}

/**
 * Implements hook_socrates_alterSettingsFolders().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function chatbot_civirules_socrates_alterSettingsFolders(&$metaDataFolders = NULL) {
  _chatbot_civirules_civix_socrates_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_socrates_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function chatbot_civirules_socrates_entityTypes(&$entityTypes) {
  _chatbot_civirules_civix_socrates_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_socrates_preProcess().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function chatbot_civirules_socrates_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_socrates_navigationMenu().
 *
 * @link http://wiki.socrates.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function chatbot_civirules_socrates_navigationMenu(&$menu) {
  _chatbot_civirules_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'socrates/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _chatbot_civirules_civix_navigationMenu($menu);
} // */
