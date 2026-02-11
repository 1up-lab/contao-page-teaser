<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\Helper;

use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Image\ImageSizes;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DcaHelper
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ImageSizes $imageSizes
    ) {
    }

    /**
     * Return all news templates as array.
     */
    public function getTeaserTemplate(): array
    {
        return Controller::getTemplateGroup('teasers_');
    }

    /**
     * Return all available image sizes for the logged in user.
     */
    public function getImageSizes(): array
    {
        /** @var BackendUser $user */
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->imageSizes->getOptionsForUser($user);
    }
}
