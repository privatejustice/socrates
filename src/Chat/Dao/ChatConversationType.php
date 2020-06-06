<?php
namespace Socrates\Chat\Dao;


/**
 * @package CRM
 * @copyright Socrates LLC (c) 2004-2018
 *
 * Generated from /buildkit/build/chatbot/sites/all/modules/socrates/tools/extensions/civicrm-chatbot/xml/schema/CRM/Chat/ChatConversationType.xml
 * DO NOT EDIT.  Generated by Socrates\Core_CodeGen
 * (GenCodeChecksum:31432f311de680794e5d2b219e32e048)
 */

/**
 * Database access object for the ChatConversationType entity.
 */
class ChatConversationType extends Socrates\Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'socrates_chat_conversation_type';

  /**
   * Should Socrates log any modifications to this table in the socrates_log table.
   *
   * @var bool
   */
  static $_log = TRUE;

  /**
   * @var int unsigned
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * Timeout in minutes for this conversation type
   *
   * @var int
   */
  public $timeout;

  /**
   * FK to question
   *
   * @var int unsigned
   */
  public $first_question_id;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'socrates_chat_conversation_type';
    parent::__construct();
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => Socrates\Utils_Type::T_INT,
          'required' => TRUE,
          'table_name' => 'socrates_chat_conversation_type',
          'entity' => 'ChatConversationType',
          'bao' => 'Socrates\Chat\Dao\ChatConversationType',
          'localizable' => 0,
        ],
        'name' => [
          'name' => 'name',
          'type' => Socrates\Utils_Type::T_STRING,
          'title' => ts('Name'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => Socrates\Utils_Type::HUGE,
          'table_name' => 'socrates_chat_conversation_type',
          'entity' => 'ChatConversationType',
          'bao' => 'Socrates\Chat\Dao\ChatConversationType',
          'localizable' => 0,
        ],
        'timeout' => [
          'name' => 'timeout',
          'type' => Socrates\Utils_Type::T_INT,
          'title' => ts('Timeout'),
          'description' => 'Timeout in minutes for this conversation type',
          'table_name' => 'socrates_chat_conversation_type',
          'entity' => 'ChatConversationType',
          'bao' => 'Socrates\Chat\Dao\ChatConversationType',
          'localizable' => 0,
        ],
        'first_question_id' => [
          'name' => 'first_question_id',
          'type' => Socrates\Utils_Type::T_INT,
          'description' => 'FK to question',
          'table_name' => 'socrates_chat_conversation_type',
          'entity' => 'ChatConversationType',
          'bao' => 'Socrates\Chat\Dao\ChatConversationType',
          'localizable' => 0,
          'html' => [
            'type' => 'EntityRef',
          ],
        ],
      ];
      Socrates\Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(Socrates\Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = Socrates\Core_DAO_AllCoreTables::getImports(__CLASS__, 'chat_conversation_type', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = Socrates\Core_DAO_AllCoreTables::getExports(__CLASS__, 'chat_conversation_type', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? Socrates\Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}