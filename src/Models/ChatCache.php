<?php
namespace Socrates\Models;

/**
 * @package   CRM
 * @copyright Socrates LLC (c) 2004-2018
 *
 * Generated from /buildkit/build/chatbot/sites/all/modules/socrates/tools/extensions/civicrm-chatbot/xml/schema/CRM/Chat/ChatCache.xml
 * DO NOT EDIT.  Generated by Socrates\Core_CodeGen
 * (GenCodeChecksum:4feef5f9c5e832dce0246709a8d582b6)
 */

/**
 * Database access object for the ChatCache entity.
 */
class ChatCache extends Model
{

    /**
     * Static instance to hold the table name.
     *
     * @var string
     */
    protected $table = 'chat_caches';

    /**
     * Should Socrates log any modifications to this table in the socrates_log table.
     *
     * @var bool
     */
    static $_log = true;

    /**
     * Unique ChatCache ID
     *
     * @var int unsigned
     */
    public $id;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var text
     */
    public $value;

    /**
     * @var datetime
     */
    public $expires;


}
