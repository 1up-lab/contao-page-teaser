<?php

$GLOBALS['TL_DCA']['tl_content']['palettes'] += [
    'page_teasers_element' => '{type_legend},name,headline,type;{teasers_legend},teasers,teasers_order,teasers_template',
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
        'sql' => 'blob NULL',
        'relation' => [
            'type' => 'hasMany',
            'load' => 'lazy',
        ],
    ],
    'teasers_order' => [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['teasers_order'],
        'sql' => 'blob NULL',
    ],
    'teasers_template' => [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['teasers_template'],
        'default' => 'teasers_list',
        'exclude' => true,
        'inputType' => 'select',
        'options_callback' => ['oneup.page_teasers.dca_helper', 'getTeaserTemplate'],
        'eval' => [
            'tl_class' => 'w50 clr',
        ],
        'sql' => "varchar(32) NOT NULL default ''",
    ],
    'hide_protected_pages' => [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['hide_protected_pages'],
        'inputType' => 'checkbox',
        'eval' => [
            'mandatory' => false,
            'isBoolean' => true,
            'tl_class' => 'w50 m12',
        ],
        'sql' => "varchar(1) NOT NULL default ''",
    ],
];

