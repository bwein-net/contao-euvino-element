<?php

declare(strict_types=1);

/*
 * This file is part of Euvino Element for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\EuvinoElement\Helper;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Oveleon\ContaoCookiebar\Cookie;
use Oveleon\ContaoCookiebar\Cookiebar;
use Symfony\Component\HttpFoundation\RequestStack;

class CookiebarHelper
{
    public const COOKIEBAR_SETTING_TYPE_NAME = 'euvino';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
    ) {
    }

    public function getCookiebarConfig(): object|null
    {
        if (!class_exists(Cookiebar::class)) {
            return null;
        }

        global $objPage;

        if (null === $objPage || $this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest())) {
            return null;
        }

        $config = Cookiebar::getConfigByPage($objPage->rootId);

        return $config ?? null;
    }

    public function getCookie(object|null $config): Cookie|null
    {
        if (null === $config) {
            return null;
        }

        foreach ($config->groups as $group) {
            foreach ($group->cookies as $cookie) {
                if ($cookie->isLocked || self::COOKIEBAR_SETTING_TYPE_NAME !== $cookie->type) {
                    continue;
                }

                return $cookie;
            }
        }

        return null;
    }
}
