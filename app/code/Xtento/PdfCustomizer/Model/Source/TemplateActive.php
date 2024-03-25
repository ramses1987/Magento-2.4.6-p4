<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Model/Source/TemplateActive.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Model\Source;

class TemplateActive extends AbstractSource
{
    /**
     * Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @return array
     */
    public function getAvailable()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
