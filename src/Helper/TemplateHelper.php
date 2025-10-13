<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\Helper;

use Contao\CoreBundle\Image\Studio\Studio;
use Contao\FrontendTemplate;
use Contao\Model\Collection;

class TemplateHelper
{
    protected $fileHelper;

    public function __construct(FileHelper $fileHelper, Studio $studio)
    {
        $this->fileHelper = $fileHelper;
        $this->studio = $studio;
    }

    public function addTeasersToTemplate(FrontendTemplate $template, Collection $teasers = null)
    {
        if (!$teasers) {
            $template->teasers = [];

            return $template;
        }

        $teaserModels = $teasers->getModels();
        $teasers = [];

        foreach ($teaserModels as $teaser) {
            if (!$teaser->published) {
                continue;
            }

            $teasers[] = $teaser;

            // Skip if no image is around
            if (!$teaser->singleSRC) {
                continue;
            }

            $teaser->singleSRC = $this->fileHelper->getFileFromUuid($teaser);

            // Skip again, if still no file around
            if (!$teaser->singleSRC) {
                continue;
            }

            $figure = $this->studio->createFigureBuilder()
                ->fromPath($teaser->singleSRC, false)
                ->setSize($teaser->size)
                ->buildIfResourceExists()
            ;

            if (null === $figure) {
                continue;
            }

            $objImage = new \stdClass();

            $figure->applyLegacyTemplateData($objImage);

            $teaser->previewImage = $objImage;
        }

        $template->teasers = $teasers;

        return $template;
    }
}
