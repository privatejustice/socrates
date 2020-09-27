<?php

namespace Socrates\Models;

use Socrates\Services\StationService;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class Activity extends Model
{
    protected $table = 'chat_activities';

    /**
     * @var string[]
     *
     * @psalm-var array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string, 6: string}
     */
    protected $fillable = [
    'target_contact_id', 
    'activity_type_id', 
    'activity_status_id', 
    'subject', 
    'details', 
    'source_contact_id', 
    'parent_id', 
    ];
    
}
