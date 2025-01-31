<?php

namespace App\Enums;

enum CampaignStatusEnum: string
{
    case DRAFT = "draft";
    case PUBLISHED = "published";
    case ONGOING = "ongoing";
    case SENT = "sent";
}
