<?php

namespace Socrates\Models;

use Socrates\Traits\HasCurrency;

class Debt extends Model
{

    use HasCurrency;

    const UPDATED_AT = null;

    public function debtor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function creditor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function getAmountFormattedAttribute(): string
    {
        return str_replace(',00', '', number_format($this->amount, 2, ',', '.')) . " {$this->currency_symbol}";
    }

    /**
     * @return \Illuminate\Contracts\Translation\Translator|array|null|string
     */
    public function toStringFromDebtor()
    {
        return trans('debts.you_have_to_pay', ['amount' => $this->amount_formatted, 'username' => $this->creditor->username]);
    }

    /**
     * @return \Illuminate\Contracts\Translation\Translator|array|null|string
     */
    public function toStringFromCreditor()
    {
        return trans('debts.you_have_to_receive', ['amount' => $this->amount_formatted, 'username' => $this->debtor->username]);
    }
}
