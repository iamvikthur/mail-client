<?php

namespace App\Enums;

enum MailboxStateEnum: string
{
    case ONLINE = "online";
    case OFFLINE = 'offline';
}
