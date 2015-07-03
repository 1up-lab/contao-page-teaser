<?php

namespace Oneup\PageTeaser\Module;

class PageTeaser extends \Module
{
    protected $strTemplate = 'mod_pageteasers';

    protected function compile()
    {
        $teasers = $this->objModel->getRelated('teasers')->getModels();

        foreach ($teasers as $teaser) {
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
    }
}
