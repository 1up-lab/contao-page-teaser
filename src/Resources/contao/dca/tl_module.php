<?php

$GLOBALS['TL_DCA']['tl_module']['palettes'] += [
    'page_teasers_individual_module' => '{title_legend},name,headline,type;{teasers_legend},teasers,teasers_order,teasers_template',
];

$GLOBALS['TL_DCA']['tl_module']['fields'] += [
    'teasers' => [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['teasers'],
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
        'label' => &$GLOBALS['TL_LANG']['tl_module']['teasers_order'],
        'sql' => "blob NULL",
    ],
    'teasers_template' => [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['teasers_template'],
        'default' => 'teasers_list',
        'exclude' => true,
        'inputType' => 'select',
        'options_callback' => ['oneup.page_teasers.dca_helper', 'getTeaserTemplate'],
        'sql' => "varchar(32) NOT NULL default ''",
    ],
];
