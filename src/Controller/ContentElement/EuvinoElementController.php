<?php

declare(strict_types=1);

/*
 * This file is part of Euvino Element for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\EuvinoElement\Controller\ContentElement;

use Bwein\EuvinoElement\Helper\CookiebarHelper;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\FrontendTemplate;
use Contao\Template;
use Oveleon\ContaoCookiebar\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ContentElement("euvino", category="includes", template="ce_euvino")
 */
class EuvinoElementController extends AbstractContentElementController
{
    public const EUVINO_SCRIPT_SRC_URL = 'https://www.euvino.eu/jsc/iframe.js';

    private $cookiebarHelper;

    public function __construct(CookiebarHelper $cookiebarHelper)
    {
        $this->cookiebarHelper = $cookiebarHelper;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $cookiebarConfig = $this->cookiebarHelper->getCookiebarConfig();
        $cookie = $this->cookiebarHelper->getCookie($cookiebarConfig);

        $this->addWidgetAssets($cookie);
        $template->euvinoCode = $this->generateElementInit($cookie, $model);

        return $template->getResponse();
    }

    protected function addWidgetAssets(Cookie|null $cookie): void
    {
        if (null !== $cookie) {
            $GLOBALS['TL_JAVASCRIPT']['bwein_euvino'] = 'bundles/bweineuvinoelement/js/euvino.js';
        } else {
            $template = new FrontendTemplate('euvino_scripts');
            $template->scriptSrc = self::EUVINO_SCRIPT_SRC_URL;
            $GLOBALS['TL_HEAD']['bwein_euvino'] = $template->parse();
        }
    }

    protected function generateElementInit(Cookie|null $cookie, ContentModel $model): string
    {
        $templateInitName = null !== $cookie ? 'euvino_init_cookiebar' : 'euvino_init';
        $template = new FrontendTemplate($templateInitName);
        $template->id = $model->id;
        $template->euvinoId = $model->euvinoId;
        $template->cookie = $cookie;

        return $template->parse();
    }
}
