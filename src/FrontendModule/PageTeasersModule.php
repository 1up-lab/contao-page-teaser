<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Oneup\ContaoPageTeaserBundle\Helper\TemplateHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageTeasersModule extends AbstractFrontendModuleController
{
    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TemplateHelper $templateHelper,
        private readonly TokenChecker $tokenChecker,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $teaserTemplateName = 'teasers_list';

        if ($this->scopeMatcher->isFrontendRequest($request) && $model->teasers_template) {
            $teaserTemplateName = $model->teasers_template;
        }

        $teaserTemplate = new FrontendTemplate($teaserTemplateName);

        $pages = PageModel::findPublishedByPid($model->rootPage, $this->tokenChecker->hasFrontendUser() ? ['having' => 'guests = 0'] : []);

        if (!$pages) {
            return new Response('');
        }

        $template->set('teasers', $this->templateHelper->addTeasersToTemplate($teaserTemplate, $pages)->parse());

        return $template->getResponse();
    }
}
