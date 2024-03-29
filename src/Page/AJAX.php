<?php
namespace Socrates\Chat\Page;

use Api;

class AJAX
{

    public static function getContactConversations(): void
    {
        $params = $_GET;
        $requiredParams = array(
        'cid' => 'Integer',
        );
        $optionalParams = array(
        'source_contact_id' => 'String',
        'status_id' => 'Integer',
        );
        $params = \Socrates\Core_Page_AJAX::defaultSortAndPagerParams();
        $params += \Socrates\Core_Page_AJAX::validateParams($requiredParams, $optionalParams);

        // get conversation list
        $conversations = self::getConversationList($params);
        Socrates\Utils_JSON::output($conversations);
    }

    static function getConversationList($params): array
    {
        $params['sequential'] = 1;
        $params['contact_id'] = $params['cid'];
        $params['activity_type_id'] = 'Conversation';

        $params['rowCount'] = $params['rp'];
        if (!empty($params['sortBy'])) {
            $params['sort'] = $params['sortBy'];
        }

        $DT['data'] = [];

        $conversations = Api::render('Activity', 'get', $params);
        $activityStatuses = array_column(Api::render('OptionValue', 'get', ['option_group_id' => 'activity_status'])['values'], 'label', 'value');
        // print_r($activityStatuses);

        foreach ($conversations['values'] as $conversation) {

            $sourceContact = Api::render(
                'Contact', 'getsingle', array(
                'return' => array("display_name"),
                'id' => $conversation['source_contact_id'],
                )
            );
            $url = \Socrates\Utils_System::url('socrates/contact/view', 'reset=1&cid='.$conversation['source_contact_id']);
            $conversation['source_contact'] = "<a href='{$url}'>{$sourceContact['display_name']}</a>";

            // Format Date
            $conversation['date'] = \Socrates\Utils_Date::customFormat($conversation['activity_date_time']);
            // $conversation['status'] = $activityStatuses[]
            // Format current question for display (show a shortened (to 30 chars) question text label)
            $links = self::actionLinks();
            // Get mask
            $mask = \Socrates\Core_Action::VIEW;
            // switch ($conversation['status_id']) {
            //   case $scheduledId:
            //     // We show delete if in scheduled state
            //     $mask += \Socrates\Core_Action::DELETE;
            //     break;
            //   case $inProgressId:
            //     // We show cancel if in "In Progress" state
            //     $mask += \Socrates\Core_Action::UPDATE;
            //     break;
            // }
            $conversation['links'] = \Socrates\Core_Action::formLink(
                $links,
                $mask,
                array(
                'id' => $conversation['id'],
                'cid' => $params['cid'],
                ),
                ts('more')
            );
            $DT['data'][] = $conversation;
        }
        $DT['recordsTotal'] = $conversations['count'];
        $DT['recordsFiltered'] = $DT['recordsTotal'];
        // var_dump($DT);exit;
        return $DT;
    }

    /**
     * @return (mixed|string)[][]
     *
     * @psalm-return array<array{name: mixed, url: 'socrates/chat/conversation/view', qs: 'id=%%id%%', title: mixed, class: 'crm-popup'}>
     */
    static function actionLinks(): array
    {
        $links = array(
        Socrates\Core_Action::VIEW => array(
        'name' => ts('View'),
        'url' => 'socrates/chat/conversation/view',
        'qs' => 'id=%%id%%',
        'title' => ts('View Conversation'),
        'class' => 'crm-popup',
        ),
        Socrates\Core_Action::DELETE => array(
        'name' => ts('Delete'),
        'url' => 'socrates/activity/add',
        'qs' => 'action=delete&id=%%id%%&cid=%%',
        'title' => ts('Delete Conversation'),
        'class' => 'crm-popup',
        ),
        );
        return $links;
    }
}
