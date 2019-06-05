<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Oneup\ContaoPageTeaserBundle\OneupContaoPageTeaserBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(OneupContaoPageTeaserBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
