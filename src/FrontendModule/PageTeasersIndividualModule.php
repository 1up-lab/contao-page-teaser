<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Database;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Contao\StringUtil;
use Oneup\ContaoPageTeaserBundle\Helper\TemplateHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageTeasersIndividualModule extends AbstractFrontendModuleController
{
    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TemplateHelper $templateHelper,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $teaserTemplateName = 'teasers_list';

        if ($this->scopeMatcher->isFrontendRequest($request) && $model->teasers_template) {
            $teaserTemplateName = $model->teasers_template;
        }

        $teaserTemplate = new FrontendTemplate($teaserTemplateName);

        $teasers = $model->getRelated('teasers', [
            'order' => Database::getInstance()->findInSet('tl_page.id', StringUtil::deserialize($model->teasers_order)),
        ]);

        $template->set('teasers', $this->templateHelper->addTeasersToTemplate($teaserTemplate, $teasers)->parse());

        return $template->getResponse();
    }
}
