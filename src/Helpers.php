<?php

/*
 * This file is part of flarumite/simple-discussion-views.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\DiscussionViews;

use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class Helpers
{

    private static function getSettings()
    {
        return Yaml::parseFile(__DIR__ . '/../settings.yml');
    }

    public static function getIpAddress(): ?string
    {
        return Arr::get($_SERVER, 'HTTP_CLIENT_IP')
            ?? Arr::get($_SERVER, 'HTTP_CF_CONNECTING_IP')
            ?? Arr::get($_SERVER, 'HTTP_X_FORWARDED_FOR')
            ?? Arr::get($_SERVER, 'REMOTE_ADDR');
    }

    public static function getUserAgentString(): ?string
    {
        return Arr::get($_SERVER, 'HTTP_USER_AGENT', self::getSettings()['NO_USER_AGENT_STRING']);
    }

    public static function increaseViewCountForUserAgent($userAgent) :bool
    {
        $settings = self::getSettings();
        if ($userAgent === $settings['NO_USER_AGENT_STRING']) {
            return false;
        }
        foreach ($settings['CRAWLER_USER_AGENTS'] as $identifier) {
            if (preg_match("/$identifier/", $userAgent) === 1) {
                return false;
            }
        }
        return true;
    }
}
