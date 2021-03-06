<?php
return array(
  'chatbot_facebook_callback_url' => array(
    'group_name' => 'Chatbot',
    'group' => 'chatbot',
    'name' => 'chatbot_facebook_callback_url',
    'type' => 'String',
    'quick_form_type' => 'Element',
    'html_type' => 'Text',
    'title' => 'Facebook callback URL',
    'description' => 'Facebook will send webhook updates to this callback URL.',
    'help_text' => 'Facebook will send webhook updates to this callback URL. Configure your Facebook app webhook to use this URL.',
    'default' => \Socrates\Utils_System::url('civicrm/chat/webhook/facebook', NULL, true),
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
  ),
  'chatbot_facebook_verify_token' => array(
    'group_name' => 'Chatbot',
    'group' => 'chatbot',
    'name' => 'chatbot_facebook_verify_token',
    'type' => 'String',
    'quick_form_type' => 'Element',
    'html_type' => 'Text',
    'title' => 'Facebook verify token',
    'description' => 'Facebook verify token',
    'help_text' => 'This string is randomly generated by CiviCRM and used to authenticate webhook updates from Facebook. You can update it to another value if you wish. Configure your Facebook app webhook to use this token.',
    'default' => '',
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
  ),
  'chatbot_facebook_page_access_token' => array(
    'group_name' => 'Chatbot',
    'group' => 'chatbot',
    'name' => 'chatbot_facebook_page_access_token',
    'type' => 'String',
    'quick_form_type' => 'Element',
    'html_type' => 'Text',
    'title' => 'Facebook page access token',
    'help_text' => 'This token is used by Facebook to authenticate messages sent from CiviCRM. Generate a Page access token in your Facebook app, and add it here.',
    'description' => 'Facebook page access token',
    'default' => '',
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
  ),
  'chatbot_facebook_app_secret' => array(
    'group_name' => 'Chatbot',
    'group' => 'chatbot',
    'name' => 'chatbot_facebook_app_secret',
    'type' => 'String',
    'quick_form_type' => 'Element',
    'html_type' => 'Text',
    'title' => 'Facebook app secret',
    'help_text' => 'This secret is used by Facebook to authenticate messages sent from CiviCRM. Find it in your Facebook app, and add it here.',
    'description' => 'Facebook app secret',
    'default' => '',
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
  ),
);
