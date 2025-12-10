<?php

namespace App\Enums;

enum StatusType: string
{
   case WAITING = "Waiting";
   case STARTED = "Started";
   case FINISHED = "Finished";
}