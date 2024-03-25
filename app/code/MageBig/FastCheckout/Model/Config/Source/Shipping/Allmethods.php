<?php
/**
 * Copyright © www.magebig.com - All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageBig\FastCheckout\Model\Config\Source\Shipping;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Option\ArrayInterface;
use Magento\Shipping\Model\Config;

/**
 * @inheritdoc
 */
class Allmethods implements ArrayInterface
{
    /**
     * Core store config
     *
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Config
     */
    protected $_shippingConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $shippingConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Config $shippingConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_shippingConfig = $shippingConfig;
    }

    /**
     * Return array of carriers.
     *
     * If $isActiveOnlyFlag is set to true, will return only active carriers
     *
     * @param bool $isActiveOnlyFlag
     * @return array
     */
    public function toOptionArray($isActiveOnlyFlag = false)
    {
        $methods = [['value' => '', 'label' => 'No']];
        $carriers = $this->_shippingConfig->getAllCarriers();
        foreach ($carriers as $carrierCode => $carrierModel) {
            if (!$carrierModel->isActive() && (bool)$isActiveOnlyFlag === true) {
                continue;
            }
            $carrierMethods = $carrierModel->getAllowedMethods();
            if (!$carrierMethods) {
                continue;
            }
            $carrierTitle = $this->_scopeConfig->getValue(
                'carriers/' . $carrierCode . '/title',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $methods[$carrierCode] = ['label' => $carrierTitle, 'value' => []];
            foreach ($carrierMethods as $methodCode => $methodTitle) {

                /** Check it $carrierMethods array was well-formed */
                if (!$methodCode) {
                    continue;
                }
                $methods[$carrierCode]['value'][] = [
                    'value' => $carrierCode . '_' . $methodCode,
                    'label' => '[' . $carrierCode . '] ' . $methodTitle,
                ];
            }
        }

        return $methods;
    }
}
