<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Database;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Contao\Template;
use Contao\StringUtil;
use Oneup\ContaoPageTeaserBundle\Helper\TemplateHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageTeasersModule extends AbstractFrontendModuleController
{
    protected $scopeMatcher;
    protected $templateHelper;

    public function __construct(ScopeMatcher $scopeMatcher, TemplateHelper $templateHelper)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->templateHelper = $templateHelper;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $teaserTemplateName = 'teasers_list';

        if ($this->scopeMatcher->isFrontendRequest($request) && $model->teasers_template) {
            $teaserTemplateName = $model->teasers_template;
        }

        $teaserTemplate = new FrontendTemplate($teaserTemplateName);

        $teasers = $model->getRelated('teasers', [
            'order' => Database::getInstance()->findInSet('tl_page.id', StringUtil::deserialize($model->teasers_order))
        ]);

        $template->teasers = $this->templateHelper->addTeasersToTemplate($teaserTemplate, $teasers)->parse();

        return $template->getResponse();
    }
}
