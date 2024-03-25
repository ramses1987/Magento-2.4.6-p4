<?php

/**
 * Product:       Xtento_PdfCustomizer
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2023-05-08T20:18:00+00:00
 * File:          app/code/Xtento/PdfCustomizer/Controller/Adminhtml/Product/Massaction/Printpdf.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\PdfCustomizer\Controller\Adminhtml\Product\Massaction;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Xtento\PdfCustomizer\Helper\GeneratePdf;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Xtento\PdfCustomizer\Model\PdfTemplateFactory;

/**
 * Class Printpdf
 * @package Xtento\PdfCustomizer\Controller\Adminhtml\Product\Massaction
 */
class Printpdf extends AbstractMassAction
{
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var PdfTemplateFactory
     */
    private $pdfTemplateFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param FileFactory $fileFactory
     * @param GeneratePdf $generatePdfHelper
     * @param OrderCollectionFactory $collectionFactory
     * @param CollectionFactory $productCollectionFactory
     * @param PdfTemplateFactory $pdfTemplateFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        FileFactory $fileFactory,
        GeneratePdf $generatePdfHelper,
        OrderCollectionFactory $collectionFactory,
        CollectionFactory $productCollectionFactory,
        PdfTemplateFactory $pdfTemplateFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->pdfTemplateFactory = $pdfTemplateFactory;
        parent::__construct($context, $filter, $fileFactory, $generatePdfHelper);
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return ResponseInterface
     */
    protected function massAction(AbstractCollection $collection)
    {
        $templateId = $this->getRequest()->getParam('template_id', null);
        $templateModel = $this->pdfTemplateFactory->create()->load($templateId);
        $storeId = 0;
        if ($templateModel && $templateModel->getId()) {
            $storeIds = $templateModel->getStoreId();
            if (!empty($storeIds)) {
                $storeId = $storeIds[0];
            }
        }
        $collection = $this->filter->getCollection(
            $this->productCollectionFactory->create()->addAttributeToSelect('*')->setStore($storeId)
        );

        $this->abstractCollection = $collection;
        return $this->generateFile();
    }
}
