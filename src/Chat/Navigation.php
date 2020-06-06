<?php
namespace Socrates\Chat;

use Socrates\Chat\ExtensionUtil as E;
class Navigation {
  static function getItems(){
    return [
      [
        'label' => E::ts('Chat'),
        'parent' => '',
        'name' => 'chatbot',
        'url' => '',
        'permission' => 'access chatbot',
        'operator' => 'OR',
        'separator' => 0,
        'weight' => 60,
      ],
      // [
      //   'label' => E::ts('Dashboard'),
      //   'parent' => 'chatbot',
      //   'name' => 'chatbot_dashboard',
      //   'url' => 'socrates/chat',
      //   'permission' => 'access chatbot',
      //   'operator' => 'OR',
      //   'separator' => 0,
      //   'weight' => 1,
      // ],
      [
        'label' => E::ts('Conversation types'),
        'parent' => 'chatbot',
        'name' => 'chatbot_dashboard',
        'url' => 'socrates/chat/conversationType',
        'permission' => 'access chatbot',
        'operator' => 'OR',
        'separator' => 0,
        'weight' => 2,
      ],
      [
        'label' => E::ts('Chat settings'),
        'parent' => 'Administer/System Settings',
        'name' => 'chatbot_settings',
        'url' => 'socrates/admin/chat',
        'permission' => 'administer Socrates',
        'operator' => 'OR',
        'separator' => 0,
        'weight' => 18.1, // Just after SMS providers
      ],
    ];
  }

}
