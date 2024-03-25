<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\TotalsItemInterface
 */
interface TotalsItemExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return int|null
     */
    public function getMbQtyIncrement();

    /**
     * @param int $mbQtyIncrement
     * @return $this
     */
    public function setMbQtyIncrement($mbQtyIncrement);
}
