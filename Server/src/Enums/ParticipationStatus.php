<?php

namespace App\Enums;

enum ParticipationStatus: string
{
   case JOINED = 'joined';
   case Completed = 'completed';
   case Failed = 'failed';
}
