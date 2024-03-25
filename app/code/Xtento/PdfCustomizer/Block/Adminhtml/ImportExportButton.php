<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Block/Adminhtml/ImportExportButton.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Block\Adminhtml;

use Magento\Backend\Block\Widget\Container;

/**
 * Class ImportExportButton
 * @package Xtento\PdfCustomizer\Block\Adminhtml
 */
class ImportExportButton extends Container
{
    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'import_export_settings',
            'label' => __('Import / Export Settings'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/importExport') . "')",
        ];
        $this->buttonList->add('import_export_settings', $addButtonProps);

        return parent::_prepareLayout();
    }
}
