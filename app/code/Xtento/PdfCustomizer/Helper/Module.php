<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Helper/Module.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Helper;

class Module extends \Xtento\XtCore\Helper\AbstractModule
{
    protected $edition = 'CE';
    protected $module = 'Xtento_PdfCustomizer';
    protected $extId = 'MTWOXtento_PdfCustomizer123412';
    protected $configPath = 'xtento_pdfcustomizer/general/';

    // Module specific functionality below

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return parent::isModuleEnabled();
    }
}
