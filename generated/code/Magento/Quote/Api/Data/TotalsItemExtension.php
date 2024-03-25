<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\TotalsItemInterface
 */
class TotalsItemExtension extends \Magento\Framework\Api\AbstractSimpleObject implements TotalsItemExtensionInterface
{
    /**
     * @return int|null
     */
    public function getMbQtyIncrement()
    {
        return $this->_get('mb_qty_increment');
    }

    /**
     * @param int $mbQtyIncrement
     * @return $this
     */
    public function setMbQtyIncrement($mbQtyIncrement)
    {
        $this->setData('mb_qty_increment', $mbQtyIncrement);
        return $this;
    }
}
