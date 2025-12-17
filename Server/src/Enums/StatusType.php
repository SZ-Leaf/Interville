<?php

namespace App\Enums;

enum StatusType: string
{
   case WAITING = "waiting";
   case STARTED = "started";
   case FINISHED = "finished";
}