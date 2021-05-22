<?php

declare(strict_types=1);

/*
 * This file is part of Euvino Element for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['euvino'] = '
    {type_legend},type,headline;
    {euvino_legend},euvinoId;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['euvinoId'] = [
    'exclude' => true,
    'filter' => true,
    'inputType' => 'text',
    'eval' => [
        'mandatory' => true,
        'chosen' => true,
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];
