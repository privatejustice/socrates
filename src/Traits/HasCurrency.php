<?php

namespace Socrates\Traits;

trait HasCurrency
{

    protected $currency_symbols = [
        'brl' => 'R$',
        'eur' => '€',
        'usd' => '$',
        'gbp' => '£'
    ];

    public function getCurrencySymbolAttribute()
    {
        return $this->currency_symbols[$this->currency ?? 'brl'];
    }
}