<?php

/**
 * Product:       Xtento_GridActions
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2023-04-06T19:30:41+00:00
 * File:          app/code/Xtento/GridActions/Controller/Adminhtml/Pdf/Shipments.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\GridActions\Controller\Adminhtml\Pdf;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Shipment;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Xtento\GridActions\Ui\Component\MassAction\CustomFilter;

/**
 * Handling print actions
 *
 * @package Xtento\GridActions\Controller\Adminhtml\Pdf
 */
class Shipments extends \Magento\Sales\Controller\Adminhtml\Order\Pdfshipments
{
    /**
     * @var \Xtento\XtCore\Helper\Utils
     */
    protected $utilsHelper;

    /**
     * Shipments constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Shipment $shipment
     * @param ShipmentCollectionFactory $shipmentCollectionFactory
     * @param CustomFilter $customFilter
     * @param \Xtento\XtCore\Helper\Utils $utilsHelper
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Shipment $shipment,
        ShipmentCollectionFactory $shipmentCollectionFactory,
        CustomFilter $customFilter,
        \Xtento\XtCore\Helper\Utils $utilsHelper
    ) {
        parent::__construct($context, $customFilter, $collectionFactory, $dateTime, $fileFactory, $shipment, $shipmentCollectionFactory);
        $this->utilsHelper = $utilsHelper;
    }

    /**
     * Print shipments for selected orders
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|\Magento\Backend\Model\View\Result\Redirect
     * @throws \Exception
     */
    protected function massAction(AbstractCollection $collection)
    {
        $shipmentsCollection = $this->shipmentCollectionFactory
            ->create()
            ->setOrderFilter(['in' => $collection->getAllIds()]);
        if (!$shipmentsCollection->getSize()) {
            $this->messageManager->addErrorMessage(__('There are no printable documents related to selected orders.'));
            return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        }

        // Support for XTENTO PDF Customizer
        if ($this->utilsHelper->isExtensionInstalled('Xtento_PdfCustomizer')) {
            $pdf = $this->_objectManager->create('\Xtento\PdfCustomizer\Helper\GeneratePdf')->generatePdfForCollection($shipmentsCollection, null);
            return $this->fileFactory->create(
                $pdf['filename'],
                $pdf['output'],
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        }

        $pdf = $this->pdfShipment->getPdf($shipmentsCollection->getItems());
        $fileContent = ['type' => 'string', 'value' => $pdf->render(), 'rm' => true];

        return $this->fileFactory->create(
            sprintf('packingslip%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
            $fileContent,
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Xtento_GridActions::ship');
    }
}
