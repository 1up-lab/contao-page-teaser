<?php

namespace Oneup\PageTeaser\Helper;

class DcaHelper extends \Backend
{
    /**
     * Return all news templates as array
     * @return array
     */
    public function getTeaserTemplate()
    {
        return $this->getTemplateGroup('teasers_');
    }

    /**
     * Dynamically add flags to the "singleSRC" field
     *
     * @param mixed         $varValue
     * @param \DataContainer $dc
     *
     * @return mixed
     */
    public function setSingleSrcFlags($varValue, \DataContainer $dc)
    {
        if ($dc->activeRecord) {
            switch ($dc->activeRecord->type) {
                case 'text':
                case 'hyperlink':
                case 'image':
                case 'accordionSingle':
                    $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['extensions'] = \Config::get('validImageTypes');
                    break;

                case 'download':
                    $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['extensions'] = \Config::get('allowedDownload');
                    break;
            }
        }

        return $varValue;
    }

    /**
     * Pre-fill the "alt" and "caption" fields with the file meta data
     *
     * @param mixed         $varValue
     * @param \DataContainer $dc
     *
     * @return mixed
     */
    public function storeFileMetaInformation($varValue, \DataContainer $dc)
    {
        if ($dc->activeRecord->singleSRC == $varValue) {
            return $varValue;
        }

        $objFile = \FilesModel::findByUuid($varValue);

        if ($objFile !== null) {
            $arrMeta = deserialize($objFile->meta);

            if (!empty($arrMeta)) {
                $objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM " . ($dc->activeRecord->ptable ?: 'tl_article') . " WHERE id=?)")
                    ->execute($dc->activeRecord->pid);

                if ($objPage->numRows) {
                    $objModel = new \PageModel();
                    $objModel->setRow($objPage->row());
                    $objModel->loadDetails();

                    // Convert the language to a locale (see #5678)
                    $strLanguage = str_replace('-', '_', $objModel->rootLanguage);

                    if (isset($arrMeta[$strLanguage])) {
                        \Input::setPost('alt', $arrMeta[$strLanguage]['title']);
                        \Input::setPost('caption', $arrMeta[$strLanguage]['caption']);
                    }
                }
            }
        }

        return $varValue;
    }
}
