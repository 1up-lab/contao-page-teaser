<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\Helper;

use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\FrontendTemplate;
use Contao\Model\Collection;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TemplateHelper
{
    public function __construct(
        private readonly InsertTagParser $insertTagParser,
        private readonly ContentUrlGenerator $contentUrlGenerator,
    ) {
    }

    public function addTeasersToTemplate(FrontendTemplate $template, ?Collection $teasers = null): FrontendTemplate
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
                    $teaser->url = $this->contentUrlGenerator->generate($teaser, [], UrlGeneratorInterface::ABSOLUTE_URL);
                } catch (\Exception $e) {
                }
            }

            $teasers[] = $teaser;
        }

        $template->teasers = $teasers;

        return $template;
    }
}
