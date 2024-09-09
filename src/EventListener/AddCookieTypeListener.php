<?php

declare(strict_types=1);

/*
 * This file is part of Euvino Element for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\EuvinoElement\EventListener;

use Bwein\EuvinoElement\Controller\ContentElement\EuvinoElementController;
use Bwein\EuvinoElement\Helper\CookiebarHelper;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Oveleon\ContaoCookiebar\Cookie;

/**
 * @Hook("addCookieType")
 */
class AddCookieTypeListener
{
    private $cookiebarHelper;

    public function __construct(CookiebarHelper $cookiebarHelper)
    {
        $this->cookiebarHelper = $cookiebarHelper;
    }

    public function __invoke(string $type, Cookie $cookie): void
    {
        if ($type === $this->cookiebarHelper::COOKIEBAR_SETTING_TYPE_NAME) {
            $GLOBALS['TL_JAVASCRIPT']['bwein_euvino'] = 'bundles/bweineuvinoelement/js/euvino.js';
            $cookie->addScript(
                'bwein_euvino.initBlocker('.$cookie->id.', [\''.EuvinoElementController::EUVINO_SCRIPT_SRC_URL.'\'])',
                Cookie::LOAD_UNCONFIRMED,
                Cookie::POS_BELOW,
            );
            $cookie->addScript(
                'bwein_euvino.initEuvinoScripts([\''.EuvinoElementController::EUVINO_SCRIPT_SRC_URL.'\'])',
                Cookie::LOAD_CONFIRMED,
                Cookie::POS_BELOW,
            );
        }
    }
}
