<?php

namespace Oneup\PageTeaser\Element;

use Oneup\PageTeaser\Base\BasePageTeaser;

class PageTeaser extends \ContentElement
{
    protected $strTemplate = 'ce_pageteasers';

    protected function compile()
    {
        $strTemplate = 'teasers_list';

        $basePageTeaser = new BasePageTeaser();

        // Use a custom template
        if (TL_MODE == 'FE' && $this->teasers_template != '') {
            $strTemplate = $this->teasers_template;
        }

        $teaserTpl = new \FrontendTemplate($strTemplate);
        $teasers   = $this->objModel->getRelated('teasers');

        $compiledTeasers = $basePageTeaser->addTeasersToTemplate($teaserTpl, $teasers);

        $this->Template->teasers = $compiledTeasers->parse();
        $this->Template->headline = $this->headline;
        $this->Template->hl = $this->hl;
    }
}
