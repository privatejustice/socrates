<?php
namespace Socrates\Models;

/**
 * @package   CRM
 * @copyright Socrates LLC (c) 2004-2018
 *
 * Generated from /buildkit/build/chatbot/sites/all/modules/socrates/tools/extensions/civicrm-chatbot/xml/schema/CRM/Chat/ChatAction.xml
 * DO NOT EDIT.  Generated by Socrates\Core_CodeGen
 * (GenCodeChecksum:5aa8d39b3dc031eb8816cdf78bb87e67)
 */

/**
 * Database access object for the ChatAction entity.
 */
class ChatAction extends Model
{

    /**
     * Static instance to hold the table name.
     *
     * @var string
     */
    protected $table = 'chat_actions';

    /**
     * Should Socrates log any modifications to this table in the socrates_log table.
     *
     * @var bool
     */
    static $_log = true;

    /**
     * Unique ChatAction ID
     *
     * @var int unsigned
     */
    public $id;

    /**
     * FK to ChatQuestion
     *
     * @var int unsigned
     */
    public $question_id;

    /**
     * @var string
     */
    public $type;

    /**
     * Serialized representation of check object
     *
     * @var text
     */
    public $check_object;

    /**
     * @var text
     */
    public $action_data;


    /**
     * Returns the names of this table
     *
     * @return string
     */
    public static function getTableName()
    {
        return self::$_tableName;
    }

}
