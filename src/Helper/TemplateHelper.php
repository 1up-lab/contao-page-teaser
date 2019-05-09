<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\Helper;

use Contao\Controller;
use Contao\FrontendTemplate;
use Contao\Model\Collection;

class TemplateHelper
{
    protected $fileHelper;

    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    public function addTeasersToTemplate(FrontendTemplate $template, Collection $teasers)
    {
        if (!$teasers) {
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

            $objImage = new \stdClass();

            Controller::addImageToTemplate($objImage, $teaser->row());

            $teaser->previewImage = $objImage;
        }

        $template->teasers = $teasers;

        return $template;
    }
}
