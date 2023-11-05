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
use Oveleon\ContaoCookiebar\CookieHandler;

/**
 * @Hook("compileCookieType")
 */
class CompileCookieTypeListener
{
    private $cookiebarHelper;

    public function __construct(CookiebarHelper $cookiebarHelper)
    {
        $this->cookiebarHelper = $cookiebarHelper;
    }

    public function __invoke(string $type, CookieHandler $cookieHandler): void
    {
        if ($type === $this->cookiebarHelper::COOKIEBAR_SETTING_TYPE_NAME) {
            $GLOBALS['TL_JAVASCRIPT']['bwein_euvino'] = 'bundles/bweineuvinoelement/js/euvino.js';
            $cookieHandler->addScript(
                'bwein_euvino.initBlocker('.$cookieHandler->id.', [\''.EuvinoElementController::EUVINO_SCRIPT_SRC_URL.'\'])',
                CookieHandler::LOAD_UNCONFIRMED,
                CookieHandler::POS_BELOW,
            );
            $cookieHandler->addScript(
                'bwein_euvino.initEuvinoScripts([\''.EuvinoElementController::EUVINO_SCRIPT_SRC_URL.'\'])',
                CookieHandler::LOAD_CONFIRMED,
                CookieHandler::POS_BELOW,
            );
        }
    }
}
