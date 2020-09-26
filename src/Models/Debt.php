<?php

namespace Socrates\Models;

use Socrates\Traits\HasCurrency;

class Debt extends Model
{

    use HasCurrency;

    const UPDATED_AT = null;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Group>
     */
    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function toStringFromDebtor(): string
    {
        return trans('debts.you_have_to_pay', ['amount' => $this->amount_formatted, 'username' => $this->creditor->username]);
    }

    public function toStringFromCreditor(): string
    {
        return trans('debts.you_have_to_receive', ['amount' => $this->amount_formatted, 'username' => $this->debtor->username]);
    }
}
