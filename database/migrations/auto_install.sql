-- +--------------------------------------------------------------------+
-- | CiviCRM version 4.7                                                |
-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC (c) 2004-2018                                |
-- +--------------------------------------------------------------------+
-- | This file is a part of CiviCRM.                                    |
-- |                                                                    |
-- | CiviCRM is free software; you can copy, modify, and distribute it  |
-- | under the terms of the GNU Affero General Public License           |
-- | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
-- |                                                                    |
-- | CiviCRM is distributed in the hope that it will be useful, but     |
-- | WITHOUT ANY WARRANTY; without even the implied warranty of         |
-- | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
-- | See the GNU Affero General Public License for more details.        |
-- |                                                                    |
-- | You should have received a copy of the GNU Affero General Public   |
-- | License and the CiviCRM Licensing Exception along                  |
-- | with this program; if not, contact CiviCRM LLC                     |
-- | at info[AT]civicrm[DOT]org. If you have questions about the        |
-- | GNU Affero General Public License or the licensing of CiviCRM,     |
-- | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the exisiting tables
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_chat_actions`;
DROP TABLE IF EXISTS `civicrm_chat_users`;
DROP TABLE IF EXISTS `civicrm_chat_questions`;
DROP TABLE IF EXISTS `civicrm_chat_hears`;
DROP TABLE IF EXISTS `civicrm_chat_conversation_types`;
DROP TABLE IF EXISTS `civicrm_chat_caches`;

SET FOREIGN_KEY_CHECKS=1;
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--


SET FOREIGN_KEY_CHECKS=0;

-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_chat_caches
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_chat_caches` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique ChatCache ID',
     `identifier` varchar(255) NOT NULL   ,
     `value` text NOT NULL   ,
     `expires` datetime NOT NULL    
,
        PRIMARY KEY (`id`)
 
    ,     UNIQUE INDEX `index_identifier`(
        identifier
  )
  ,     INDEX `expires_key`(
        expires
  )
  
 
)    ;

-- /*******************************************************
-- *
-- * civicrm_chat_conversation_types
-- *
-- *******************************************************/
CREATE TABLE `civicrm_chat_conversation_types` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  ,
     `name` varchar(255) NOT NULL   ,
     `timeout` int    COMMENT 'Timeout in minutes for this conversation type',
     `first_question_id` int unsigned    COMMENT 'FK to question' 
,
        PRIMARY KEY (`id`)
 
 
 
)    ;

-- /*******************************************************
-- *
-- * civicrm_chat_hears
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_chat_hears` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique ChatHear ID',
     `chat_conversation_type_id` int unsigned    COMMENT 'FK to Contact',
     `text` varchar(255) NOT NULL    
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_chat_hear_chat_conversation_type_id FOREIGN KEY (`chat_conversation_type_id`) REFERENCES `civicrm_chat_conversation_types`(`id`) ON DELETE CASCADE  
)    ;

-- /*******************************************************
-- *
-- * civicrm_chat_questions
-- *
-- *******************************************************/
CREATE TABLE `civicrm_chat_questions` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique ChatQuestion ID',
     `text` text NOT NULL   ,
     `conversation_type_id` int unsigned NOT NULL   COMMENT 'FK to conversation type' 
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_chat_questions_conversation_type_id FOREIGN KEY (`conversation_type_id`) REFERENCES `civicrm_chat_conversation_types`(`id`) ON DELETE CASCADE  
)    ;

-- /*******************************************************
-- *
-- * civicrm_chat_users
-- *
-- * Connects chat service user accounts to contacts
-- *
-- *******************************************************/
CREATE TABLE `civicrm_chat_users` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique ID',
     `contact_id` int unsigned NOT NULL   COMMENT 'FK to Contact',
     `service` varchar(255) NOT NULL   COMMENT 'Service that the user account belongs to',
     `user_id` varchar(255) NOT NULL   COMMENT 'User identifier' 
,
        PRIMARY KEY (`id`)
 
    ,     INDEX `index_service`(
        service
  )
  ,     INDEX `index_user_id`(
        user_id
  )
  
,          CONSTRAINT FK_civicrm_chat_user_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE  
)    ;

-- /*******************************************************
-- *
-- * civicrm_chat_actions
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_chat_actions` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique ChatAction ID',
     `question_id` int unsigned    COMMENT 'FK to ChatQuestion',
     `type` varchar(255) NOT NULL   ,
     `check_object` text NOT NULL   COMMENT 'Serialized representation of check object',
     `action_data` text NOT NULL   ,
     `weight` int unsigned NULL  DEFAULT 0 COMMENT 'Weight (useful for questions)' 
,
        PRIMARY KEY (`id`)
 
    ,     INDEX `index_type`(
        type
  )
  
,          CONSTRAINT FK_civicrm_chat_actions_question_id FOREIGN KEY (`question_id`) REFERENCES `civicrm_chat_questions`(`id`) ON DELETE CASCADE  
)    ;

 
SET FOREIGN_KEY_CHECKS=1;