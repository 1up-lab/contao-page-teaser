<?php

declare(strict_types=1);

namespace Oneup\PageTeaser\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Database;
use Contao\FrontendTemplate;
use Contao\Template;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageTeasersElement extends AbstractContentElementController
{
    protected $scopeMatcher;

    public function __construct(ScopeMatcher $scopeMatcher)
    {
        $this->scopeMatcher = $scopeMatcher;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $teaserTemplateName = 'teasers_list';

        if ($this->scopeMatcher->isFrontendRequest($request) && $model->teasers_template) {
            $teaserTemplateName = $model->teasers_template;
        }

        $teaserTemplate = new FrontendTemplate($teaserTemplateName);

        $teasers = $model->getRelated('teasers', [
            'order' => Database::getInstance()->findInSet('tl_page.id', StringUtil::deserialize($model->teasers_order))
        ]);

        $template->teasers = $teaserTemplate->parse();
        $template->headline = $model->headline;
        $template->hl = $model->hl;
    }
}
