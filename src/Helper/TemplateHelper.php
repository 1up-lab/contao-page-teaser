<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\Helper;

use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\FrontendTemplate;
use Contao\Model\Collection;
use Contao\StringUtil;

class TemplateHelper
{
    protected $fileHelper;

    public function __construct(FileHelper $fileHelper, Studio $studio, InsertTagParser $insertTagParser)
    {
        $this->fileHelper = $fileHelper;
        $this->studio = $studio;
        $this->insertTagParser = $insertTagParser;
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

            $teaser->previewText = $this->insertTagParser->replace((string) StringUtil::restoreBasicEntities($teaser->previewText));
            $teaser->url = $this->insertTagParser->replace((string) $teaser->url);

            if ($teaser instanceof PageModel) {
                try {
                    $teaser->url = $teaser->getFrontendUrl();
                } catch (\Exception $e) {
                }
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
