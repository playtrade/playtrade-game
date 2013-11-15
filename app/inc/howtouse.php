<?php
/**
 * Examples of how the API is used.
 *
 * PHP version 5.3.5
 *
 * @category  PMailer
 * @package   Api
 * @copyright 2012 Prefix Technologies
 * @link      http://www.pmailer.co.za/
 */


require_once ('subscription_api.php');


$url = 'localhost/workspace/pmailer/src/www';
$apiKey = 'zyb8IwQVRf71siy1Pv8HO5szmv0jqppW';

$api = new MailerSubscriptionApiV1_0($url, $apiKey);

// Contact - create
$contact = array();
$contact['contact_mobile'] = '0726785544';
$contact['contact_email'] = 'bob@test.com';
$contact['contact_name'] = 'bob';
$contact['contact_lastname'] = 'jones';
$listIds = array(1, 2);
$result = $api->createContact($contact, $listIds);

// Contact - get
$createdContactId = $result['contact_id'];
$gotContact = $api->getContactById($createdContactId);

// Contact - getBatch
$filter = array();
$filter['contact_country_id'] = '1';
$order = array();
$order['contact_name'] = ORDER_ASC;
$result = $api->getContacts($filter, $order);

// Contact - getLists
$result = $api->getContactSubscriptions(1);

// Contact - createBatch
$contactA = array();
$contactA['contact_mobile'] = '0726785511';
$contactA['contact_email'] = 'bob1@test.com';
$contactA['contact_name'] = 'bob1';
$contactA['contact_lastname'] = 'jones1';
$contactB = array();
$contactB['contact_mobile'] = '0726785522';
$contactB['contact_email'] = 'bob2@test.com';
$contactB['contact_name'] = 'bob2';
$contactB['contact_lastname'] = 'jones2';
$listIds = array(1, 2);
$result = $api->createContactBatch(array($contactA, $contactB), $listIds);

// Contact - update
$updates = array();
$updates['contact_name'] = 'test';
$result = $api->updateContact(1, $updates);

// Contact - updateBatch
$properties = array();
$properties['contact_name'] = 'test2';
$update = array();
$update['identifier'] = 1;
$update['properties'] = $properties;
$result = $api->updateContactBatch(array($update));

// Contact - updateSubscriptions
$lists = array();
$lists['1'] = 'subscribed';
$result = $api->updateContactSubscriptions(1, $lists);

// Contact - delete
$result = $api->deleteContact($createdContactId);

// Contact - deleteBatch
$batchIdsToBeDeleted = array();
$result = $api->deleteContactBatch(array($createdContactId + 1, $createdContactId + 2));

// List - getBatch
$filter = array();
$filter['list_group_id'] = 0;
$order = array();
$order['list_name'] = ORDER_ASC;
$result = $api->getLists($filter, $order);

// List - create
$list = array();
$list['list_name'] = 'testing_list';
$list['list_owner_name'] = 'john';
$list['list_owner_email'] = 'jh@example.com';
$result = $api->createList($list);
$createdListId = $result['list_id'];

// List - get
$gotList = $api->getListById($createdListId);

// List - getContacts
$result = $api->getContactsOnList($createdListId);

// List - update
$updates = array();
$updates['list_company_name'] = 'team rocket';
$result = $api->updateList($createdListId, $updates);

// List - emptyList
$result = $api->emptyList($createdListId);

// List - delete
$result = $api->deleteList($createdListId);

// List - combine
$list1 = array();
$list1['list_list_group_id'] = 0;
$list1['list_name'] = 'testing_list1';
$list1['list_owner_name'] = 'john1';
$list1['list_owner_email'] = 'jh1@example.com';
$result1 = $api->createList($list1);
$authList = 1;
$nonAuthList = $result1['list_id'];
$result = $api->combineLists($authList, $nonAuthList);

// Message - getBatch
$filter = array();
$filter['message_type'] = MESSAGE_TYPE_EMAIL;
$order = array();
$order['message_subject'] = ORDER_ASC;
$result = $api->getMessages($filter, $order);

// Message - create
$message = array();
$message['message_subject'] = 'a test message';
$message['message_from_email'] = 'message@test.com';
$message['message_from_name'] = 'johnny tester';
$message['message_reply_email'] = 'jt@test.com';
$using = array('template_id' => 1);
$result = $api->createMessage(MESSAGE_TYPE_EMAIL, $using, $message);
$createdMessageId = $result['message_id'];

// Message - get
$gotMessage = $api->getMessageById($createdMessageId);

// Message - update
$updates = array();
$updates['message_from_name'] = 'Michael';
$result = $api->updateMessage($createdMessageId, $updates);

// Message - delete
$result = $api->deleteMessage($createdMessageId);

// Template - get
$result = $api->getTemplateById(1);

// Template - getBatch
$result = $api->getTemplates();