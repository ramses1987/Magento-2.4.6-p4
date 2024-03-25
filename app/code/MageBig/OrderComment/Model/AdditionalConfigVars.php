<?php

namespace MageBig\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AdditionalConfigVars implements ConfigProviderInterface
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    protected const ORDER_COMMENTS = 'magebig_order_comment/general/enable';
    protected const PRINT_INVOICE_COMMENT = 'magebig_order_comment/general/print_invoice';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get config
     *
     * @return array|mixed
     */
    public function getConfig()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        $enabledComment = $this->scopeConfig->getValue(self::ORDER_COMMENTS, $storeScope);
        if ($enabledComment) {
            $additionalVariables['enabled_comment'] = true;
        } else {
            $additionalVariables['enabled_comment'] = false;
        }

        return $additionalVariables;
    }

    /**
     * Enable/disable pdf invoice comment
     *
     * @return bool
     */
    public function isCommentPdf()
    {
        return (bool)$this->scopeConfig->getValue(self::PRINT_INVOICE_COMMENT, ScopeInterface::SCOPE_STORE);
    }
}
