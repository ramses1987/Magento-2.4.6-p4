<?php

/**
 * Product:       Xtento_CustomOrderNumber
 * ID:            4GB1a7y5z4mmfO6TfCiFtUXfMdyFV1IWYsYZLEd/wFY=
 * Last Modified: 2016-04-06T20:34:22+00:00
 * File:          app/code/Xtento/CustomOrderNumber/Logger/Handler.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */
namespace Xtento\CustomOrderNumber\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/xtento_customordernumber.log';
}