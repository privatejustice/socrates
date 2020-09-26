<?php

namespace Socrates\Http\Controllers;

use Socrates\Models\User;
use BotMan\BotMan\BotMan;

class DebtsController extends Controller
{


    public function createFromMe(BotMan $bot, $amount, $creditorUsername)
    {
        $debtor = auth()->user();
        $creditor = User::where('username', $creditorUsername)->first();
        $group = $debtor->group;

        if (! $creditor || ! $group->users()->find($creditor->id)) {
            return $bot->reply(trans('errors.user_not_found', ['username' => $creditorUsername]));
        }

        $creditor->pays($amount)->to($debtor)->in($group)->save();

        return $bot->reply(trans('debts.add.debt_me'));
    }

    public function createFromOthers(BotMan $bot, $debtorUsername, $amount)
    {
        $debtor = User::where('username', $debtorUsername)->first();
        $creditor = auth()->user();
        $group = $creditor->group;

        if (! $debtor || ! $group->users()->find($debtor->id)) {
            return $bot->reply(trans('errors.user_not_found', ['username' => $debtorUsername]));
        }

        $creditor->pays($amount)->to($debtor)->in($group)->save();

        return $bot->reply(trans('debts.add.debt_others'));
    }
}
