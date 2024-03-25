<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2019-02-05T17:13:45+00:00
 * File:          app/code/Xtento/PdfCustomizer/Controller/Adminhtml/Order/Massaction/Printpdf.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Ui\Component\MassAction\Filter;
use Xtento\PdfCustomizer\Helper\GeneratePdf;

/**
 * Class Printpdf
 *
 * @package Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Printpdf extends AbstractMassAction
{
    /**
     * Printpdf constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param FileFactory $fileFactory
     * @param ForwardFactory $resultForwardFactory
     * @param GeneratePdf $generatePdfHelper
     * @param OrderCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        FileFactory $fileFactory,
        ForwardFactory $resultForwardFactory,
        GeneratePdf $generatePdfHelper,
        OrderCollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter, $fileFactory, $resultForwardFactory, $generatePdfHelper);
    }

    /**
     * @param AbstractCollection $collection
     * @return ResponseInterface
     */
    //@codingStandardsIgnoreLine
    protected function massAction(AbstractCollection $collection)
    {
        $this->abstractCollection = $collection;
        return $this->generateFile();
    }
}
