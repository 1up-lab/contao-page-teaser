<?php

declare(strict_types=1);

namespace Oneup\ContaoPageTeaserBundle\Helper;

use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\Image\ImageSizes;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DcaHelper
{
    protected $database;
    protected $tokenStorage;
    protected $imageSizes;

    public function __construct(Connection $database, TokenStorageInterface $tokenStorage, ImageSizes $imageSizes)
    {
        $this->database = $database;
        $this->tokenStorage = $tokenStorage;
        $this->imageSizes = $imageSizes;
    }

    /**
     * Return all news templates as array.
     *
     * @return array
     */
    public function getTeaserTemplate(): array
    {
        return Controller::getTemplateGroup('teasers_');
    }

    /**
     * Return all available image sizes for the logged in user.
     *
     * @return array
     */
    public function getImageSizes(): array
    {
        /** @var BackendUser $user */
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->imageSizes->getOptionsForUser($user);
    }
}
