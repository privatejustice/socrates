<?php
namespace Socrates\Chat\Page\ConversationType;

use Socrates\Chat\ExtensionUtil as E;
use Api;

class View extends Socrates\Core_Page
{

    public function run()
    {

        // TODO Implement paging so we can display more that 25 conversation types :)

        $id = \Socrates\Utils_Request::retrieve('id', 'Positive', $this);

        $conversationType = Api::render('ChatConversationType', 'getsingle', ['id' => $id]);

        Socrates\Utils_System::setTitle(E::ts('Conversation type: %1', [$conversationType['name']]));

        $this->assign('conversationType', $conversationType);

        $questions = Api::render(
            'ChatQuestion', 'get', [
            'conversation_type_id' => $id,
            ]
        )['values'];
        $this->assign('questions', $questions);

        if(count($questions)) {

            $actionParams = [
            'question_id' => ['IN' => array_keys($questions)],
            'options' => [ 'sort' => 'weight ASC' ]
            ];

            // Group actions by question, order by type, then weight (for those where weight is significant)
            $groupActions = Api::render('ChatAction', 'get', array_merge($actionParams, ['type' => 'group']))['values'];
            $fieldActions = Api::render('ChatAction', 'get', array_merge($actionParams, ['type' => 'field']))['values'];
            $sayActions = Api::render('ChatAction', 'get', array_merge($actionParams, ['type' => 'say']))['values'];
            $conversationActions = Api::render('ChatAction', 'get', array_merge($actionParams, ['type' => 'conversation']))['values'];
            $nextActions = Api::render('ChatAction', 'get', array_merge($actionParams, ['type' => 'next']))['values'];

            foreach($nextActions as $key => $nextAction) {
                if(!in_array($nextAction['action_data'], array_keys($questions))) {
                    unset($nextActions[$key]);
                }
            }

            $actions = [];

            foreach(array_merge(
                $groupActions,
                $fieldActions,
                $sayActions,
                $conversationActions,
                $nextActions
            ) as $action){
                $actionCheck = unserialize($action['check_object']);
                if($actionCheck instanceof Socrates\Check_Anything) {
                    $action['check_text'] = '';
                }else{
                    $action['check_text'] = $actionCheck->summarise();
                }
                $actions[$action['question_id']][$action['id']] = $action;
            }

            if(count($actions)) {
                $this->assign('actions', $actions);
            }

            // Order questions by creating and flattening a question tree
            $tree[$conversationType['first_question_id']] = [];
            $questionOrder = [];
            $this->addBranches($tree, $nextActions);
            $this->order($questions, $tree, $orderedQuestions);

            $this->assign('orderedQuestions', $orderedQuestions);

            if($conversationActions) {
                $this->assign('conversations', Api::render('ChatConversationType', 'get', ['id' => ['IN' => array_column($conversationActions, 'action_data')]])['values']);
            }
            if($groupActions) {
                $this->assign('groups', Api::render('Group', 'get', ['id' => ['IN' => array_column($groupActions, 'action_data')]])['values']);
            }


            $this->assign('questionMap', array_column($orderedQuestions, 'number', 'id'));
            $this->assign('missingQuestions', array_diff(array_column($questions, 'id'), array_column($orderedQuestions, 'id')));
        }
        parent::run();

    }

    // Creates a tree based on likely routes through the questions
    function addBranches(&$root, &$nextActions)
    {
        foreach($root as $questionId => &$child) {
            foreach($nextActions as $actionId => &$action){
                if($action['question_id'] == $questionId) {
                    $child[$action['action_data']] = [];
                    foreach($nextActions as $actionToDeleteId => $actionToDelete){
                        if($actionToDelete['action_data'] == $action['action_data']) {
                            unset($nextActions[$actionToDeleteId]);
                        }
                    }
                    $this->addBranches($child, $nextActions);
                }
            }
        }
    }

    // Orders questions based on the tree, adding a consecutive number for each so
    // they can be cross referenced.
    function order($questions, $tree, &$orderedQuestions, $number = 0)
    {
        foreach($tree as $key => $branch) {
            $number++;
            $orderedQuestions[$number] = $questions[$key];
            $orderedQuestions[$number]['number'] = $number;
            if(count($branch)) {
                $this->order($questions, $branch, $orderedQuestions, $number);
                $number++;
            }
        }
    }
}
