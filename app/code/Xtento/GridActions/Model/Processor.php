<?php

/**
 * Product:       Xtento_GridActions
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2023-07-18T18:47:19+00:00
 * File:          app/code/Xtento/GridActions/Model/Processor.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\GridActions\Model;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class Processor
 * @package Xtento\GridActions\Model
 */
class Processor
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Xtento\GridActions\Helper\Module
     */
    protected $moduleHelper;

    /**
     * @var \Xtento\XtCore\Helper\Utils
     */
    protected $utilsHelper;

    /**
     * @var \Xtento\XtCore\Helper\Shipping
     */
    protected $shippingHelper;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $orderStatuses;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender
     */
    protected $orderCommentSender;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    protected $invoiceSender;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\ShipmentSender
     */
    protected $shipmentSender;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Sales\Model\Order\Shipment\TrackFactory
     */
    protected $trackFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $dbTransactionFactory;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $adminhtmlHelper;

    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    protected $shipmentFactory;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var ShipmentExtensionFactory
     */
    protected $shipmentExtensionFactory;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Processor constructor.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Xtento\GridActions\Helper\Module $moduleHelper
     * @param \Xtento\XtCore\Helper\Utils $utilsHelper
     * @param \Xtento\XtCore\Helper\Shipping $shippingHelper
     * @param \Magento\Sales\Model\Order\Config $orderStatuses
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender
     * @param \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender
     * @param \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $shipmentSender
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Sales\Model\Order\Shipment\TrackFactory $trackFactory
     * @param \Magento\Framework\DB\TransactionFactory $dbTransactionFactory
     * @param \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory
     * @param \Magento\Backend\Helper\Data $adminhtmlData
     * @param \Magento\Sales\Model\OrderRepository $orderRepository
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Xtento\GridActions\Helper\Module $moduleHelper,
        \Xtento\XtCore\Helper\Utils $utilsHelper,
        \Xtento\XtCore\Helper\Shipping $shippingHelper,
        \Magento\Sales\Model\Order\Config $orderStatuses,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $shipmentSender,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $trackFactory,
        \Magento\Framework\DB\TransactionFactory $dbTransactionFactory,
        \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory,
        \Magento\Backend\Helper\Data $adminhtmlData,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        ObjectManagerInterface $objectManager
    ) {
        $this->request = $request;
        $this->registry = $registry;
        $this->scopeConfig = $config;
        $this->messageManager = $messageManager;
        $this->moduleHelper = $moduleHelper;
        $this->utilsHelper = $utilsHelper;
        $this->shippingHelper = $shippingHelper;
        $this->orderStatuses = $orderStatuses;
        $this->orderSender = $orderSender;
        $this->orderCommentSender = $orderCommentSender;
        $this->invoiceSender = $invoiceSender;
        $this->shipmentSender = $shipmentSender;
        $this->orderFactory = $orderFactory;
        $this->trackFactory = $trackFactory;
        $this->dbTransactionFactory = $dbTransactionFactory;
        $this->shipmentFactory = $shipmentFactory;
        $this->adminhtmlHelper = $adminhtmlData;
        $this->orderRepository = $orderRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @return bool
     */
    public function processOrders($orderIds)
    {
        if (!$this->moduleHelper->isModuleEnabled()) {
            return false;
        }
        if (function_exists('set_time_limit')) {
            try {
                set_time_limit(0);
            } catch (\Exception $e) {}
        }

        $actionsToRun = $this->request->getParam('actions');
        if (!is_array($orderIds) || empty($actionsToRun)) {
            $this->messageManager->addErrorMessage(__('Please select order(s) and actions to run on orders.'));
            return false;
        }

        $tracksAndCarriers = [];

        // Order status modifications
        $orderStatuses = $this->orderStatuses->getStatuses();
        $completeStatus = $this->scopeConfig->getValue('gridactions/general/change_status_complete');
        if ($completeStatus == '') {
            $completeStatus = 'no_change';
        } else {
            if (!array_key_exists($completeStatus, $orderStatuses) && $completeStatus !== 'no_change') {
                $this->messageManager->addErrorMessage(
                    __(
                        'The custom order status which should be set for an order after completing it does not ' .
                            'exist anymore. Please make sure you set a valid custom order status at System > XTENTO ' .
                            'Extensions > Simplify Bulk Order Processing. Processing stopped.'
                    )
                );
                return false;
            }
        }
        $shipStatus = $this->scopeConfig->getValue('gridactions/general/change_status_ship');
        if ($shipStatus == '') {
            $shipStatus = 'no_change';
        } else {
            if (!array_key_exists($shipStatus, $orderStatuses) && $shipStatus !== 'no_change') {
                $this->messageManager->addErrorMessage(
                    __(
                        'The custom order status which should be set for an order after shipping it does not exist ' .
                            'anymore. Please make sure you set a valid custom order status at System > XTENTO ' .
                            'Extensions > Simplify Bulk Order Processing. Processing stopped.'
                    )
                );
                return false;
            }
        }
        $invoiceStatus = $this->scopeConfig->getValue('gridactions/general/change_status_invoice');
        if ($invoiceStatus == '') {
            $invoiceStatus = 'no_change';
        } else {
            if (!array_key_exists($invoiceStatus, $orderStatuses) && $invoiceStatus !== 'no_change') {
                $this->messageManager->addErrorMessage(
                    __(
                        'The custom order status which should be set for an order after invoicing it does not ' .
                            'exist anymore. Please make sure you set a valid custom order status at System > XTENTO ' .
                            'Extensions > Simplify Bulk Order Processing. Processing stopped.'
                    )
                );
                return false;
            }
        }

        if (!$this->moduleHelper->confirmEnabled(true) || !$this->moduleHelper->isModuleEnabled()) {
            $this->messageManager->addErrorMessage(
                __(
                    str_rot13(
                        'Guvf bcrengvba pbhyqa\'g or pbzcyrgrq. Cyrnfr znxr fher lbh ner hfvat n inyvq yvprafr ' .
                        'xrl va gur zbqhyrf pbasvthengvba ng Flfgrz > KGRAGB Rkgrafvbaf.'
                    )
                )
            );
            return false;
        }
        // Check if this module was made for the edition (CE/PE/EE) it's being run in
        if ($this->moduleHelper->isWrongEdition()) {
            $this->messageManager->addComplexErrorMessage(
                'backendHtmlMessage',
                [
                    'html' => (string)__(
                        'Attention: The installed extension version is not compatible with Magento Enterprise Edition. The compatibility of the currently installed extension version has only been confirmed with Magento Community Edition. Please go to <a href="https://www.xtento.com" target="_blank">www.xtento.com</a> to purchase or download the Enterprise Edition version of this extension.'
                    )
                ]
            );
            return false;
        }

        // Defined actions:
        $doInvoice = false;
        $doForceCapture = false;
        $doShip = false;
        $doComplete = false;
        $doDelete = false;
        $doNotify = false;
        $doPrintDocuments = false;
        $doChangeStatus = false;
        $doForceEmail = false;
        $doForceOrderEmail = false;
        $doUncancel = false;

        if (!strstr($actionsToRun, '_setstatus') || strstr($actionsToRun, '_complete') !== false) {
            if (strstr($actionsToRun, '_invoice')) {
                $doInvoice = true;
            }
            if (strstr($actionsToRun, '_capture')) {
                $doForceCapture = true;
            }
            if (strstr($actionsToRun, '_ship')) {
                $tracksAndCarriers = $this->parseTracksAndCarriers();
                $doShip = true;
            }
            if (strstr($actionsToRun, '_complete')) {
                $doComplete = true;
            }
            if (strstr($actionsToRun, '_delete')) {
                $doDelete = true;
            }
            if (strstr($actionsToRun, '_notify')) {
                $doNotify = true;
            }
            if (strstr($actionsToRun, '_print')) {
                $doPrintDocuments = true;
            }
            if (strstr($actionsToRun, '_forcenotification')) {
                $doForceEmail = true;
            }
            if (strstr($actionsToRun, '_forceorderemail')) {
                $doForceOrderEmail = true;
            }
            if (strstr($actionsToRun, '_uncancel')) {
                $doUncancel = true;
            }
        } else {
            if (strstr($actionsToRun, '_setstatus')) {
                $doChangeStatus = true;
                $newOrderStatus = str_replace('_setstatus_', '', $actionsToRun);
            }
        }

        // Other settings
        $doCapture = $this->scopeConfig->isSetFlag('gridactions/general/do_capture');
        $setPaid = $this->scopeConfig->isSetFlag('gridactions/general/set_paid');

        $modifiedCount = 0;
        foreach ($orderIds as $orderId) {
            try {
                $isModified = false;

                /** @var \Magento\Sales\Model\Order $order */
                $order = $this->orderRepository->get($orderId);
                if (!$order || !$order->getId()) {
                    $this->messageManager->addErrorMessage(
                        __(
                            'Could not process order with entity_id %1. Order has been deleted in the meantime?',
                            $orderId
                        )
                    );
                    continue;
                }
                $oldStatus = $order->getStatus();

                if (($doInvoice || $doShip || $doComplete) && $order->getStatus(
                ) == \Magento\Sales\Model\Order::STATE_HOLDED
                ) {
                    $order->unhold()->save();
                }

                if ($doForceOrderEmail) {
                    $this->orderSender->send($order);
                    $isModified = true;
                }

                if ($doUncancel && $order->getStatus() == \Magento\Sales\Model\Order::STATE_CANCELED) {
                    $order->setStatus(\Magento\Sales\Model\Order::STATE_PROCESSING);
                    $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING);
                    $order->save();

                    foreach ($order->getAllItems() as $item) {
                        $item->setQtyCanceled(0);
                        $item->save();
                    }
                    $isModified = true;
                }

                if ($doInvoice && !$doForceEmail && $order->canInvoice()) {
                    if ($this->invoiceOrder($order, $doNotify, $doCapture, $setPaid)) {
                        $isModified = true;
                    }
                    #$order = $this->orderFactory->create()->load($order->getId());
                }
                if ($doInvoice && $doForceEmail) {
                    $invoices = $order->getInvoiceCollection();
                    foreach ($invoices as $invoice) {
                        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
                        $invoice->setCustomerNoteNotify(true);
                        $this->invoiceSender->send($invoice);
                        $invoice->save();
                    }
                }

                if ($doForceCapture) {
                    foreach ($order->getInvoiceCollection() as $invoice) {
                        if ($invoice->canCapture()) {
                            $invoice->capture();
                            $invoice->getOrder()->setIsInProcess(true);
                            $transactionSave = $this->dbTransactionFactory->create()
                                ->addObject($invoice)->addObject($invoice->getOrder());
                            $transactionSave->save();
                            $isModified = true;
                        }
                    }
                }

                if ($doShip && !$doForceEmail && !$order->canShip() && isset($tracksAndCarriers[$orderId])) {
                    // Order has been already shipped.. add another tracking number.
                    if ($this->scopeConfig->isSetFlag('gridactions/general/add_trackingnumber_from_grid_shipped')) {
                        // Add a second/third/whatever tracking number to the shipment - if possible.
                        $shipments = $order->getShipmentsCollection();
                        /** @var \Magento\Sales\Model\Order\Shipment $lastShipment */
                        $lastShipment = $shipments->getFirstItem();
                        if ($lastShipment->getId()) {
                            $this->setMsiSource($lastShipment);
                            foreach ($tracksAndCarriers[$orderId] as $trackData) {
                                $trackingNumber = $trackData['tracking_number'];
                                $carrierCode = $trackData['carrier'];
                                $carrierName = $this->shippingHelper->determineCarrierTitle(
                                    $carrierCode,
                                    $order->getShippingDescription(),
                                    $order->getStoreId()
                                );

                                if (empty($carrierCode) && !empty($carrierName)) {
                                    $carrierCode = $carrierName;
                                }
                                if (empty($carrierName) && !empty($carrierCode)) {
                                    $carrierName = $carrierCode;
                                }
                                $trackAlreadyAdded = false;
                                foreach ($lastShipment->getAllTracks() as $trackInfo) {
                                    if ($trackInfo->getTrackNumber() == $trackingNumber) {
                                        $trackAlreadyAdded = true;
                                        break;
                                    }
                                }
                                if (!$trackAlreadyAdded) {
                                    if (!empty($trackingNumber)) {
                                        // Determine carrier and add tracking number
                                        $trackingNumber = str_replace("'", "", $trackingNumber);
                                        $track = $this->trackFactory->create()->addData(
                                            [
                                                'carrier_code' => $carrierCode,
                                                'title' => $carrierName,
                                                'track_number' => $trackingNumber
                                            ]
                                        );

                                        $lastShipment->addTrack($track)->save();
                                        $isModified = true;

                                        if ($doNotify) {
                                            $this->shipmentSender->send($lastShipment);
                                        }
                                    }
                                }
                                unset($lastShipment);
                            }
                        }
                    }
                }

                if ($doShip && !$doForceEmail && $order->canShip()) {
                    if ($this->shipOrder($order, $tracksAndCarriers, $doNotify)) {
                        $isModified = true;
                    }
                    #$order = $this->orderFactory->create()->load($order->getId());
                }

                if ($doShip && $doForceEmail) {
                    $shipments = $order->getShipmentsCollection();
                    foreach ($shipments as $shipment) {
                        $shipment->setCustomerNoteNotify(true);
                        $this->setMsiSource($shipment);
                        $this->shipmentSender->send($shipment);
                        $shipment->save();
                        $isModified = true;
                    }
                }

                if ($doComplete && $completeStatus == 'no_change') {
                    $this->setOrderState($order, \Magento\Sales\Model\Order::STATE_COMPLETE);
                    $order->setStatus(\Magento\Sales\Model\Order::STATE_COMPLETE);
                    $order->save();
                    $isModified = true;
                } else {
                    if ($doComplete && $completeStatus !== 'no_change') {
                        if ($order->getStatus() !== $completeStatus) {
                            $this->setOrderState($order, $completeStatus);
                            $order->setStatus($completeStatus)->save();
                            $isModified = true;
                        }
                    } else {
                        if ($doShip && $shipStatus !== 'no_change') {
                            if ($order->getStatus() !== $shipStatus) {
                                $this->setOrderState($order, $shipStatus);
                                $order->setStatus($shipStatus)->save();
                            }
                        } else {
                            if ($doInvoice && $invoiceStatus !== 'no_change') {
                                if ($order->getStatus() !== $invoiceStatus) {
                                    $this->setOrderState($order, $invoiceStatus);
                                    $order->setStatus($invoiceStatus)->save();
                                }
                            } else {
                                if ($doChangeStatus && !empty($newOrderStatus)) {
                                    #if ($order->getStatus() !== $newOrderStatus) {
                                    $this->setOrderState($order, $newOrderStatus);
                                    $order->setStatus($newOrderStatus)->save();
                                    #}
                                    $isModified = true;
                                }
                            }
                        }
                    }
                }
                if ($oldStatus !== $order->getStatus()) {
                    $order->addStatusHistoryComment('', $order->getStatus())->setIsCustomerNotified($doNotify);

                    // Xtento_AdvancedOrderStatus compatibility
                    if ($this->registry->registry('advancedorderstatus_notifications')) {
                        $this->orderCommentSender->send($order);
                    }
                    // End
                    $order->save();
                }

                if ($doDelete) {
                    $this->registry->register('isSecureArea', true, true);
                    foreach ($order->getInvoiceCollection() as $invoice) {
                        $invoice->delete();
                    }
                    foreach ($order->getShipmentsCollection() as $shipment) {
                        $shipment->delete();
                    }
                    foreach ($order->getCreditmemosCollection() as $creditmemo) {
                        $creditmemo->delete();
                    }
                    $this->orderRepository->delete($order);
                    $this->registry->unregister('isSecureArea');
                    $isModified = true;
                }

                if ($isModified) {
                    $modifiedCount++;
                }
            } catch (\Exception $e) {
                if (isset($order) && $order && $order->getIncrementId()) {
                    $orderId = $order->getIncrementId();
                }
                $this->messageManager->addErrorMessage('Exception (Order # ' . $orderId . '): ' . $e->getMessage());
            }
        }

        if ($doPrintDocuments) {
            // maybe? '_secure' => $this->scopeResolver->getScope()->isCurrentlySecure(
            if ($doInvoice) {
                $this->messageManager->addComplexNoticeMessage(
                    'backendHtmlMessage',
                    [
                        'html' =>
                            (string)__(
                                'Click <a href="%1" target="_blank">here</a> to print the invoice PDF for processed orders.',
                                $this->adminhtmlHelper->getUrl(
                                    'gridactions/pdf/invoices',
                                    [
                                        'order_ids' => implode(",", $orderIds),
                                        'namespace' => 'sales_order_grid'
                                    ]
                                )
                            )
                    ]
                );
            }
            if ($doShip) {
                $this->messageManager->addComplexNoticeMessage(
                    'backendHtmlMessage',
                    [
                        'html' =>
                            (string)
                            __(
                                'Click <a href="%1" target="_blank">here</a> to print the packing slip PDF ' .
                                'for processed orders.',
                                $this->adminhtmlHelper->getUrl(
                                    'gridactions/pdf/shipments',
                                    [
                                        'order_ids' => implode(",", $orderIds),
                                        'namespace' => 'sales_order_grid'
                                    ]
                                )
                            )
                    ]
                );
                $this->messageManager->addComplexNoticeMessage(
                    'backendHtmlMessage',
                    [
                        'html' =>
                            (string)
                            __(
                                'Click <a href="%1" target="_blank">here</a> to print the shipping label PDF ' .
                                'for processed orders.',
                                $this->adminhtmlHelper->getUrl(
                                    'gridactions/pdf/labels',
                                    ['order_ids' => implode(",", $orderIds)]
                                )
                            )
                    ]
                );
            }
        }

        $this->messageManager->addSuccessMessage(__('Total of %1 order(s) were modified.', $modifiedCount));

        return true;
    }

    /**
     * @param $order \Magento\Sales\Model\Order
     * @param $doNotify
     * @param $doCapture
     * @param $setPaid
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function invoiceOrder(&$order, $doNotify, $doCapture, $setPaid)
    {
        /** @var $invoice \Magento\Sales\Model\Order\Invoice */
        $invoice = $order->prepareInvoice();
        if ($doCapture && $invoice->canCapture() && $this->doCapturePaymentMethod(
            (string)$order->getPayment()->getMethod()
        )
        ) {
            // Capture order online
            $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
        } else {
            if ($setPaid) {
                // Set invoice status to Paid
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_OFFLINE);
            } else {
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::NOT_CAPTURE);
            }
        }
        $invoice->register();
        $invoice->setCustomerNoteNotify($doNotify);

        $invoice->getOrder()->setIsInProcess(true);

        $transactionSave = $this->dbTransactionFactory->create()
            ->addObject($invoice)->addObject($invoice->getOrder());
        $transactionSave->save();

        if ($doNotify) {
            $this->invoiceSender->send($invoice);
        }

        return true;
    }

    /**
     * @param $order \Magento\Sales\Model\Order
     * @param $tracksAndCarriers
     * @param $doNotify
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function shipOrder(&$order, $tracksAndCarriers, $doNotify)
    {
        $items = [];
        foreach ($order->getAllItems() as $orderItem) {
            $items[$orderItem->getId()] = $orderItem->getQtyToShip();
        }
        /** @var \Magento\Sales\Model\Order\Shipment $shipment */
        $shipment = $this->shipmentFactory->create($order, $items);
        $this->setMsiSource($shipment);
        $shipment->register();
        $shipment->setCustomerNoteNotify($doNotify);
        $shipment->getOrder()->setIsInProcess(true);

        if (isset($tracksAndCarriers[$order->getId()])) {
            foreach ($tracksAndCarriers[$order->getId()] as $trackData) {
                $trackingNumber = $trackData['tracking_number'];
                if (!empty($trackingNumber)) {
                    $carrierCode = $trackData['carrier'];
                    $carrierName = $this->shippingHelper->determineCarrierTitle(
                        $carrierCode,
                        $order->getShippingDescription(),
                        $order->getStoreId()
                    );
                    if (empty($carrierCode) && !empty($carrierName)) {
                        $carrierCode = $carrierName;
                    }
                    if (empty($carrierName) && !empty($carrierCode)) {
                        $carrierName = $carrierCode;
                    }
                    $trackingNumber = str_replace("'", "", $trackingNumber);

                    $track = $this->trackFactory->create()->addData(
                        [
                            'carrier_code' => $carrierCode,
                            'title' => $carrierName,
                            'track_number' => $trackingNumber
                        ]
                    );

                    $shipment->addTrack($track);
                }
            }
        }

        $transactionSave = $this->dbTransactionFactory->create()
            ->addObject($shipment)->addObject($shipment->getOrder());
        $transactionSave->save();

        if ($doNotify) {
            $this->shipmentSender->send($shipment);
        }

        return true;
    }


    /**
     * Set Magento 2.3 MSI source. Must use ObjectManager as otherwise code would not be compatible with Magento <2.3
     *
     * @param $shipment
     * @param bool $sourceCode
     *
     * @return $this
     */
    protected function setMsiSource($shipment, $sourceCode = false)
    {
        if (version_compare($this->utilsHelper->getMagentoVersion(), '2.3', '<') || !$this->utilsHelper->isExtensionInstalled('Magento_Inventory') || !$this->utilsHelper->isExtensionInstalled('Magento_InventorySalesApi') || !$this->utilsHelper->isExtensionInstalled('Magento_InventoryShippingAdminUi')) {
            return $this;
        }

        $sourceCode = false;
        $shipmentExtension = $shipment->getExtensionAttributes();
        if ($shipmentExtension && $shipmentExtension->getSourceCode()) {
            // Already set by MSI
            $sourceCode = $shipmentExtension->getSourceCode();
        }
        if (empty($shipmentExtension)) {
            $shipmentExtension = $this->objectManager->create('Magento\Sales\Api\Data\ShipmentExtensionFactory')->create();
        }
        // Determine source code
        $order = $shipment->getOrder();
        foreach ($order->getAllItems() as $orderItem) {
            if ($orderItem->getIsVirtual()
                || $orderItem->getLockedDoShip()
                || $orderItem->getHasChildren()) {
                continue;
            }

            $item = $orderItem->isDummy(true) ? $orderItem->getParentItem() : $orderItem;
            $qty = $item->getSimpleQtyToShip();
            $sku = $this->objectManager->create('Magento\InventorySalesApi\Model\GetSkuFromOrderItemInterface')->execute($item);
            $sources = $this->objectManager->create('Magento\InventoryShippingAdminUi\Ui\DataProvider\GetSourcesByOrderIdSkuAndQty')->execute($order->getId(), $sku, $qty);
            if (isset($sources[0]) && isset($sources[0]['sourceCode'])) {
                $sourceCode = $sources[0]['sourceCode'];
                break;
            }
        }
        if ($sourceCode === false) {
            // Get default source
            $sourceCode = $this->objectManager->create('Magento\InventoryCatalogApi\Api\DefaultSourceProviderInterface')->getCode();
        }
        $shipmentExtension->setSourceCode($sourceCode);
        $shipment->setExtensionAttributes($shipmentExtension);
    }

    /**
     * @param $order \Magento\Sales\Model\Order
     * @param $newOrderStatus
     * @return bool
     */
    protected function setOrderState(&$order, $newOrderStatus)
    {
        /*if ($this->scopeConfig->isSetFlag('gridactions/general/force_status_change')) {
            return;
        }*/
        $orderStates = $this->orderStatuses->getStates();
        // Check current order state with priority, as a status can be assigned to multiple states
        foreach ($orderStates as $state => $label) {
            if ($state == $order->getState()) {
                foreach ($this->orderStatuses->getStateStatuses($state, false) as $status) {
                    if ($status == $newOrderStatus) {
                        return true; // Correct state already set
                    }
                }
                break;
            }
        }
        // Status to set is from different state, find state and set
        foreach ($orderStates as $state => $label) {
            $stateStatuses = $this->orderStatuses->getStateStatuses($state, false);
            foreach ($stateStatuses as $status) {
                if ($status == $newOrderStatus) {
                    $order->setData('state', $state);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return array
     */
    protected function parseTracksAndCarriers()
    {
        if ($this->registry->registry('xtDisabled') !== false) {
            return [];
        }
        $carriersArray = [];
        $tracksAndCarriers = [];
        $carriers = $this->request->getPost('carriers', []);
        $tracks = $this->request->getPost('trackingnumbers', []);
        foreach ($carriers as $rawCarrier) {
            if ($rawCarrier == '') {
                continue;
            }
            list($orderId, $carrier) = explode("[|]", (string)$rawCarrier);
            if (!empty($orderId)) {
                $carriersArray[$orderId] = $carrier;
            }
        }
        foreach ($tracks as $rawTrack) {
            if ($rawTrack == '') {
                continue;
            }
            list($orderId, $trackingNumbers) = explode("[|]", (string)$rawTrack);
            if (!empty($orderId)) {
                foreach (explode(";", (string)$trackingNumbers) as $trackingNumber) {
                    $carrier = 'custom';
                    if (isset($carriersArray[$orderId])) {
                        $carrier = $carriersArray[$orderId];
                    }
                    $tracksAndCarriers[$orderId][] = ['carrier' => $carrier, 'tracking_number' => $trackingNumber];
                }
            }
        }
        return $tracksAndCarriers;
    }

    protected function doCapturePaymentMethod($methodCode)
    {
        if (in_array(
            $methodCode,
            explode(",", (string)$this->scopeConfig->getValue('gridactions/general/capture_methods_excluded'))
        )
        ) {
            return false;
        }
        return true;
    }
}
