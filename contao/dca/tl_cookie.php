<?php

declare(strict_types=1);

/*
 * This file is part of Euvino Element for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

use Bwein\EuvinoElement\Helper\CookiebarHelper;

if (isset($GLOBALS['TL_DCA']['tl_cookie'])) {
    $GLOBALS['TL_DCA']['tl_cookie']['palettes'][CookiebarHelper::COOKIEBAR_SETTING_TYPE_NAME] =
        '{title_legend},title,token,expireTime,provider,type;{description_legend:hide},blockDescription,description;published;';

    $GLOBALS['TL_DCA']['tl_cookie']['fields']['type']['options'][] =
        CookiebarHelper::COOKIEBAR_SETTING_TYPE_NAME;

    $GLOBALS['TL_DCA']['tl_cookie']['fields']['token']['load_callback'][] =
        static function ($varValue, $dc): void {
            if (CookiebarHelper::COOKIEBAR_SETTING_TYPE_NAME === $dc->activeRecord->type) {
                $GLOBALS['TL_DCA']['tl_cookie']['fields'][$dc->field]['eval']['mandatory'] = false;
            }
        };
}
