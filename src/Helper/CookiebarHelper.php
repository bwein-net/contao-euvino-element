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
use Oveleon\ContaoCookiebar\Cookiebar;
use Oveleon\ContaoCookiebar\CookieHandler;
use Symfony\Component\HttpFoundation\RequestStack;

class CookiebarHelper
{
    public const COOKIEBAR_SETTING_TYPE_NAME = 'euvino';

    private $requestStack;
    private $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher)
    {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    public function getCookiebarConfig(): ?object
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

    public function getCookieHandler(?object $config): ?CookieHandler
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
