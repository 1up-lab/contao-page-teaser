<?php

namespace Oneup\PageTeaser\Base;

class BasePageTeaser
{
    public function getFileFromUuid($obj)
    {
        // check if singleSRC is a path already
        if (!\Validator::isUuid($obj->singleSRC) && is_file(TL_ROOT . '/' . $obj->singleSRC))
        {
            return $obj->singleSRC;
        }

        if ($obj->singleSRC == '')
        {
            return null;
        }

        $objFile = \FilesModel::findByUuid($obj->singleSRC);

        if ($objFile === null)
        {
            if (!\Validator::isUuid($obj->singleSRC))
            {
                throw new \Exception($GLOBALS['TL_LANG']['ERR']['version2format']);
            }

            return null;
        }

        if (!is_file(TL_ROOT . '/' . $objFile->path))
        {
            return null;
        }

        return $objFile->path;
    }

    public function addTeasersToTemplate(\FrontendTemplate $template, \Model\Collection $teasers)
    {
        if (!$teasers) {
            return $template;
        }

        $teaserModels = $teasers->getModels();
        $teasers = [];

        foreach ($teaserModels as $teaser) {
            if (!$teaser->published) {
                continue;
            }

            $teasers[] = $teaser;

            if (!$teaser->singleSRC) {
                continue;
            }

            $teaser->singleSRC = $this->getFileFromUuid($teaser);

            $objImage = new \stdClass();

            \Controller::addImageToTemplate($objImage, $teaser->row());

            $teaser->previewImage = $objImage;
        }

        $template->teasers = $teasers;

        return $template;
    }
}
