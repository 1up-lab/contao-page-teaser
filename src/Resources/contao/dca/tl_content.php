<?php

use Oneup\PageTeaser\Dca\DcaHelper;

$GLOBALS['TL_DCA']['tl_content']['palettes'] += [
    'page_teasers' => '{title_legend},name,headline,type;{teasers_legend},teasers,teasers_order,teasers_template',
];

$GLOBALS['TL_DCA']['tl_content']['fields'] += [
    'teasers' => [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['teasers'],
        'exclude' => true,
        'inputType' => 'pageTree',
        'foreignKey' => 'tl_page.title',
        'eval' => [
            'multiple' => true,
            'fieldType' => 'checkbox',
            'files' => true,
            'orderField' => 'teasers_order',
            'mandatory' => true,
        ],
        'sql' => "blob NULL",
        'relation' => [
            'type' => 'hasMany',
            'load' => 'lazy',
        ],
    ],
    'teasers_order' => [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['teasers_order'],
        'sql' => "blob NULL",
    ],
    'teasers_template' => [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['teasers_template'],
        'default' => 'teasers_list',
        'exclude' => true,
        'inputType' => 'select',
        'options_callback' => [DcaHelper::class, 'getTeaserTemplate'],
        'sql' => "varchar(32) NOT NULL default ''",
    ],
];
