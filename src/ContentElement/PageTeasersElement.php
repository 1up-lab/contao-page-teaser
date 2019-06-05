<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Database;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Contao\Template;
use Oneup\ContaoPageTeaserBundle\Helper\TemplateHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageTeasersElement extends AbstractContentElementController
{
    protected $scopeMatcher;
    protected $templateHelper;

    public function __construct(ScopeMatcher $scopeMatcher, TemplateHelper $templateHelper)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->templateHelper = $templateHelper;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $teaserTemplateName = 'teasers_list';

        if ($this->scopeMatcher->isFrontendRequest($request) && $model->teasers_template) {
            $teaserTemplateName = $model->teasers_template;
        }

        $teaserTemplate = new FrontendTemplate($teaserTemplateName);

        $teasers = $model->getRelated('teasers', [
            'order' => Database::getInstance()->findInSet('tl_page.id', StringUtil::deserialize($model->teasers_order)),
        ]);

        $template->teasers = $this->templateHelper->addTeasersToTemplate($teaserTemplate, $teasers)->parse();

        return $template->getResponse();
    }
}
