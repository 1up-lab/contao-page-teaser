<?php

declare(strict_types=1);

namespace Oneup\PageTeaser\Helper;

use Contao\FilesModel;
use Contao\Validator;

class FileHelper
{
    protected $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getFileFromUuid($obj): ?string
    {
        // check if singleSRC is a path already
        if (!Validator::isUuid($obj->singleSRC) && is_file(sprintf('%s/%s', $this->projectDir, $obj->singleSRC))) {
            return $obj->singleSRC;
        }

        if ($obj->singleSRC == '') {
            return null;
        }

        $objFile = FilesModel::findByUuid($obj->singleSRC);

        if ($objFile === null) {
            if (!Validator::isUuid($obj->singleSRC)) {
                throw new \Exception($GLOBALS['TL_LANG']['ERR']['version2format']);
            }

            return null;
        }

        if (!\is_file(sprintf('%s/%s', $this->projectDir, $objFile->path))) {
            return null;
        }

        return $objFile->path;
    }
}
