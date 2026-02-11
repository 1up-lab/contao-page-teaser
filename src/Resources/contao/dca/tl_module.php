<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['palettes'] = [
    ...$GLOBALS['TL_DCA']['tl_module']['palettes'],
    ...[
        'page_teasers_individual_module' => '
            {title_legend},name,type,headline;
            {teasers_legend},teasers,teasers_order,teasers_template;
        ',
        'page_teasers_module' => '
            {title_legend},name,type,headline;
            {nav_legend},levelOffset,showLevel,hardLimit,showProtected,showHidden;
            {reference_legend:hide},defineRoot;
            {teasers_legend},teasers_template;
        ',
    ],
];

$GLOBALS['TL_DCA']['tl_module']['fields'] = [
    ...$GLOBALS['TL_DCA']['tl_module']['fields'],
    ...[
        'hide_protected_pages' => [
            'label' => &$GLOBALS['TL_LANG']['tl_module']['hide_protected_pages'],
            'inputType' => 'checkbox',
            'eval' => [
                'mandatory' => false,
                'isBoolean' => true,
                'tl_class' => 'w50 m12',
            ],
            'default' => 0,
            'save_callback' => [static fn ($v): int => '' !== $v ? 1 : 0],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::BOOLEAN,
                'default' => false,
            ],
        ],
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
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::BLOB,
                'length' => Doctrine\DBAL\Platforms\AbstractMySQLPlatform::LENGTH_LIMIT_BLOB,
                'notnull' => false,
            ],
            'relation' => [
                'type' => 'hasMany',
                'load' => 'lazy',
            ],
        ],
        'teasers_order' => [
            'label' => &$GLOBALS['TL_LANG']['tl_module']['teasers_order'],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::BLOB,
                'length' => Doctrine\DBAL\Platforms\AbstractMySQLPlatform::LENGTH_LIMIT_BLOB,
                'notnull' => false,
            ],
        ],
        'teasers_template' => [
            'label' => &$GLOBALS['TL_LANG']['tl_module']['teasers_template'],
            'default' => 'teasers_list',
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => ['oneup.page_teasers.dca_helper', 'getTeaserTemplate'],
            'eval' => [
                'tl_class' => 'w50 clr',
            ],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::STRING,
                'length' => 32,
                'notnull' => false,
                'default' => '',
            ],
        ],
    ],
];
