<?php

namespace App\Enums;

enum QuickMailStateEnum: string
{
    case DORMANT = 'dormant';
    case INQUEUE = 'inqueue';
    case SENDING = 'sending';
    case FINISHED = 'finished';
}
