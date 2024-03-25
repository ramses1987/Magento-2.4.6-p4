<?php
namespace PL\Psigate\Controller\HTMLMessenger\Redirect;

/**
 * Interceptor class for @see \PL\Psigate\Controller\HTMLMessenger\Redirect
 */
class Interceptor extends \PL\Psigate\Controller\HTMLMessenger\Redirect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Store\Model\StoreManagerInterface $storeManager, \PL\Psigate\Helper\Data $psigateHelper, \PL\Psigate\Logger\Logger $plLogger)
    {
        $this->___init();
        parent::__construct($context, $orderFactory, $checkoutSession, $storeManager, $psigateHelper, $plLogger);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
