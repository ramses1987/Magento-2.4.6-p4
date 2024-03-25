<?php

/**
 * Product:       Xtento_GridActions
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2023-04-06T19:30:41+00:00
 * File:          app/code/Xtento/GridActions/Controller/Adminhtml/Pdf/Invoices.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\GridActions\Controller\Adminhtml\Pdf;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory;
use Xtento\GridActions\Ui\Component\MassAction\CustomFilter;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Handling print actions
 *
 * @package Xtento\GridActions\Controller\Adminhtml\Pdf
 */
class Invoices extends \Magento\Sales\Controller\Adminhtml\Order\Pdfinvoices
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Xtento\XtCore\Helper\Utils
     */
    protected $utilsHelper;

    /**
     * Invoices constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Invoice $pdfInvoice
     * @param CustomFilter $customFilter
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Xtento\XtCore\Helper\Utils $utilsHelper
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Invoice $pdfInvoice,
        CustomFilter $customFilter,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Xtento\XtCore\Helper\Utils $utilsHelper
    ) {
        parent::__construct($context, $customFilter, $collectionFactory, $dateTime, $fileFactory, $pdfInvoice);
        $this->productMetadata = $productMetadata;
        $this->utilsHelper = $utilsHelper;
    }

    /**
     * Print invoices for selected orders
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    protected function massAction(AbstractCollection $collection)
    {
        if ($this->utilsHelper->mageVersionCompare($this->productMetadata->getVersion(), '2.1.3', '>=')) {
            // Starting from Magento 2.1.3, $collection returns order IDs
            $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('order_id', ['in' => $collection->getAllIds()]);
        } else {
            $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('entity_id', ['in' => $collection->getAllIds()]);
        }
        if (!$invoicesCollection->getSize()) {
            $this->messageManager->addError(__('There are no printable documents related to selected orders.'));
            return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        }

        // Support for XTENTO PDF Customizer
        if ($this->utilsHelper->isExtensionInstalled('Xtento_PdfCustomizer')) {
            $pdf = $this->_objectManager->create('\Xtento\PdfCustomizer\Helper\GeneratePdf')->generatePdfForCollection($invoicesCollection, null);
            return $this->fileFactory->create(
                $pdf['filename'],
                $pdf['output'],
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        }

        return $this->fileFactory->create(
            sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
            $this->pdfInvoice->getPdf($invoicesCollection->getItems())->render(),
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Xtento_GridActions::invoice');
    }
}
