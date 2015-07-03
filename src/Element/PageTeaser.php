<?php

namespace Oneup\PageTeaser\Element;

class PageTeaser extends \ContentElement
{
    protected $strTemplate = 'ce_pageteasers';

    protected function compile()
    {
        $teasers = $this->objModel->getRelated('teasers');

        if (!$teasers) {
            return;
        }

        $teaserModels = $teasers->getModels();
        $teasers = [];

        foreach ($teaserModels as $teaser) {
            if (!$teaser->published) {
                continue;
            }

            $teasers[] = $teaser;

            if (!$teaser->singleSRC) {
                continue;
            }

            $teaser->previewImage = \FilesModel::findByUuid($teaser->singleSRC);
        }

        $strTemplate = 'teasers_list';

        // Use a custom template
        if (TL_MODE == 'FE' && $this->teasers_template != '') {
            $strTemplate = $this->teasers_template;
        }

        $teaserTpl = new \FrontendTemplate($strTemplate);
        $teaserTpl->teasers = $teasers;

        $this->Template->teasers = $teaserTpl->parse();
        $this->Template->headline = $this->headline;
        $this->Template->hl = $this->hl;
    }
}
