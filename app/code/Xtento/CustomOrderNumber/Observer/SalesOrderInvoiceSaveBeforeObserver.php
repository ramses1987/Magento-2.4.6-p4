<?php

/**
 * Product:       Xtento_CustomOrderNumber
 * ID:            4GB1a7y5z4mmfO6TfCiFtUXfMdyFV1IWYsYZLEd/wFY=
 * Last Modified: 2020-07-20T14:54:49+00:00
 * File:          app/code/Xtento/CustomOrderNumber/Observer/SalesOrderInvoiceSaveBeforeObserver.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\CustomOrderNumber\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesOrderInvoiceSaveBeforeObserver extends AbstractObserver implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->updateIncrementId($observer->getInvoice(), self::TYPE_INVOICE);
    }

    /**
     * @param $object \Magento\Sales\Model\Order
     * @return \Magento\Sales\Model\ResourceModel\Order\Invoice\Collection
     */
    public function getCollectionForOrder($object)
    {
        return $object->getInvoiceCollection();
    }
}
