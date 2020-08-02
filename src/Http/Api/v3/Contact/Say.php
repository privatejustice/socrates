<?php
use Socrates\Chat\ExtensionUtil as E;

use Api;

function Socrates\Api\V3\contact_say($params) {

  $required = [
    'id',
    'service',
    'text'
  ];

  $missingFields = array_diff($required, array_keys($params));

  if(count($missingFields)){
    throw new API_Exception('Mandatory key(s) missing from params array: ' . implode(', ', $missingFields));
  }

  try {
    $user = Api::render('ChatUser', 'getsingle', [
      'service' => $params['service'],
      'contact_id' => $params['id']
    ]);
  } catch (\Exception $e) {
    throw new API_Exception("Could not find {$params['service']} user for contact_id {$params['id']}");
  }

  $botman = \Socrates\Chat\Botman::get($params['service']);

  $botman->middleware->sending(new \Socrates\Http\Middleware\Identify());
  $botman->middleware->sending(new \Socrates\Http\Middleware\RecordOutgoing());

  $botman->say($params['text'], $user['user_id'], Socrates\Chat\Botman::getDriver($params['service']), ['contact_id' => $params['id']]);

  return Socrates\Api\V3\create_success();
}
