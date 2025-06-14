<?php

namespace App\Enums;

enum SocialProvider: string
{
    case Google = 'google';
    case Facebook = 'facebook';
    case Twitter = 'twitter';
}