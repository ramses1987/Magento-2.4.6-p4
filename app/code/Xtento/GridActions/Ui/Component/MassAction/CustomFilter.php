<?php

/**
 * Product:       Xtento_GridActions
 * ID:            cxBzYyRMG9nxeghcP4p60/nEyx3JDNzeMJ8aE6wpMuk=
 * Last Modified: 2018-09-02T09:38:36+00:00
 * File:          app/code/Xtento/GridActions/Ui/Component/MassAction/CustomFilter.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\GridActions\Ui\Component\MassAction;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class Filter
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomFilter extends \Magento\Ui\Component\MassAction\Filter
{
    /**
     * @param AbstractDb $collection
     *
     * @return AbstractDb
     * @throws LocalizedException
     */
    public function getCollection(AbstractDb $collection)
    {
        if (method_exists($collection, 'setOrderFilter')) {
            $collection->setOrderFilter(['in' => explode(",", $this->request->getParam('order_ids'))]);
        } else {
            $collection->addFieldToFilter('entity_id', ['in' => explode(",", $this->request->getParam('order_ids'))]);
        }
        return $collection;
    }
}
