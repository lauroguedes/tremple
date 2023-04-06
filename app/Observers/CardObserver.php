<?php

namespace App\Observers;

use App\Models\Card;

/**
 * Summary of CardObserver
 */
class CardObserver
{
    public function creating(Card $card)
    {
        $card->position = Card::where('column_id', $card->column_id)->max('position') + 1000;
    }
}
