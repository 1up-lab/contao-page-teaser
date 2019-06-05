<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
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

        $pages = PageModel::findPublishedSubpagesWithoutGuestsByPid($model->rootPage, $model->showHidden);

        if (!$pages) {
            return new Response('');
        }

        $template->teasers = $this->templateHelper->addTeasersToTemplate($teaserTemplate, $pages)->parse();

        return $template->getResponse();
    }
}
