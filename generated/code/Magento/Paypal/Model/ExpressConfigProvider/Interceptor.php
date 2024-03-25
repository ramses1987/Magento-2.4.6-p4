<?php
namespace Magento\Paypal\Model\ExpressConfigProvider;

/**
 * Interceptor class for @see \Magento\Paypal\Model\ExpressConfigProvider
 */
class Interceptor extends \Magento\Paypal\Model\ExpressConfigProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Paypal\Model\ConfigFactory $configFactory, \Magento\Framework\Locale\ResolverInterface $localeResolver, \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, \Magento\Paypal\Helper\Data $paypalHelper, \Magento\Payment\Helper\Data $paymentHelper, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Paypal\Model\SmartButtonConfig $smartButtonConfig)
    {
        $this->___init();
        parent::__construct($configFactory, $localeResolver, $currentCustomer, $paypalHelper, $paymentHelper, $urlBuilder, $smartButtonConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfig');
        return $pluginInfo ? $this->___callPlugins('getConfig', func_get_args(), $pluginInfo) : parent::getConfig();
    }
}
