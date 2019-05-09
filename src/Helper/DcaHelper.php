<?php

declare(strict_types=1);

namespace Oneup\PageTeaser\Helper;

use Contao\BackendUser;
use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\Image\ImageSizes;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\Input;
use Contao\PageModel;
use Contao\StringUtil;
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
     * Return all news templates as array
     *
     * @return array
     */
    public function getTeaserTemplate(): array
    {
        return Controller::getTemplateGroup('teasers_');
    }

    /**
     * Return all available image sizes for the logged in user
     *
     * @return array
     */
    public function getImageSizes(): array
    {
        /** @var BackendUser $user */
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->imageSizes->getOptionsForUser($user);
    }

    /**
     * Dynamically add flags to the "singleSRC" field
     *
     * @param $varValue
     * @param DataContainer $dc
     *
     * @return mixed
     */
    public function setSingleSrcFlags($varValue, DataContainer $dc)
    {
        if ($dc->activeRecord) {
            switch ($dc->activeRecord->type) {
                case 'text':
                case 'hyperlink':
                case 'image':
                case 'accordionSingle':
                    $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['extensions'] = Config::get('validImageTypes');
                    break;

                case 'download':
                    $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['extensions'] = Config::get('allowedDownload');
                    break;
            }
        }

        return $varValue;
    }

    /**
     * Pre-fill the "alt" and "caption" fields with the file meta data
     *
     * @param $varValue
     * @param DataContainer $dc
     *
     * @return mixed
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function storeFileMetaInformation($varValue, DataContainer $dc)
    {
        if ($dc->activeRecord->singleSRC == $varValue) {
            return $varValue;
        }

        $objFile = FilesModel::findByUuid($varValue);

        if ($objFile !== null) {
            $arrMeta = StringUtil::deserialize($objFile->meta);

            if (!empty($arrMeta)) {

                /** @var PageModel $objPage */
                $objPage = $this->database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM " . ($dc->activeRecord->ptable ?: 'tl_article') . " WHERE id=?)")
                    ->execute($dc->activeRecord->pid);

                if ($objPage->numRows) {
                    $objModel = new PageModel();
                    $objModel->setRow($objPage->row());
                    $objModel->loadDetails();

                    // Convert the language to a locale (see #5678)
                    $strLanguage = str_replace('-', '_', $objModel->rootLanguage);

                    if (isset($arrMeta[$strLanguage])) {
                        Input::setPost('alt', $arrMeta[$strLanguage]['title']);
                        Input::setPost('caption', $arrMeta[$strLanguage]['caption']);
                    }
                }
            }
        }

        return $varValue;
    }
}
