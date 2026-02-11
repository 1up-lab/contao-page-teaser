<?php

declare(strict_types=1);

Contao\System::loadLanguageFile('tl_explain');

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'addImage';

$search = 'description;';

if (str_contains($GLOBALS['TL_DCA']['tl_page']['palettes']['regular'], 'serpPreview;')) {
    $search = 'serpPreview;';
}

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(
    $search,
    sprintf('%s{pageteaser_legend},previewText,addImage;', $search),
    $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['forward'] = str_replace(
    '{redirect_legend}',
    '{pageteaser_legend},previewText,addImage;{redirect_legend}',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['forward']
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['redirect'] = str_replace(
    '{redirect_legend}',
    '{pageteaser_legend},previewText,addImage;{redirect_legend}',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['redirect']
);

$GLOBALS['TL_DCA']['tl_page']['subpalettes'] += [
    'addImage' => 'singleSRC,size,alt,imageTitle',
];

$GLOBALS['TL_DCA']['tl_page']['fields'] = [
    ...$GLOBALS['TL_DCA']['tl_page']['fields'],
    ...[
        'addImage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['addImage'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => [
                'submitOnChange' => true,
            ],
            'default' => 0,
            'save_callback' => [static fn ($v): int => '' !== $v ? 1 : 0],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::BOOLEAN,
                'default' => false,
            ],
        ],
        'alt' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['alt'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'maxlength' => 255,
                'tl_class' => 'w50',
            ],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::STRING,
                'default' => '',
            ],
        ],
        'imageTitle' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['imageTitle'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'maxlength' => 255,
                'tl_class' => 'w50',
            ],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::STRING,
                'default' => '',
            ],
        ],
        'previewText' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['previewText'],
            'exclude' => true,
            'inputType' => 'textarea',
            'search' => true,
            'eval' => [
                'mandatory' => false,
                'rte' => 'tinyMCE',
                'helpwizard' => true,
                'tl_class' => 'clr',
                'style' => 'height:60px',
            ],
            'explanation' => 'insertTags',
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::TEXT,
                'length' => Doctrine\DBAL\Platforms\AbstractMySQLPlatform::LENGTH_LIMIT_MEDIUMTEXT,
                'notnull' => false,
            ],
        ],
        'singleSRC' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['singleSRC'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => [
                'filesOnly' => true,
                'fieldType' => 'radio',
                'mandatory' => true,
                'tl_class' => 'clr',
            ],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::BLOB,
                'length' => Doctrine\DBAL\Platforms\AbstractMySQLPlatform::LENGTH_LIMIT_BLOB,
                'notnull' => false,
            ],
        ],
        'size' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['size'],
            'exclude' => true,
            'inputType' => 'imageSize',
            'options_callback' => ['oneup.page_teasers.dca_helper', 'getImageSizes'],
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => [
                'rgxp' => 'natural',
                'includeBlankOption' => true,
                'nospace' => true,
                'helpwizard' => true,
                'tl_class' => 'clr',
            ],
            'sql' => [
                'type' => Doctrine\DBAL\Types\Types::STRING,
                'length' => 64,
                'notnull' => false,
                'default' => '',
            ],
        ],
    ],
];
