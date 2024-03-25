<?php
namespace Magento\Sales\Block\Adminhtml\Order\Invoice\View;

/**
 * Interceptor class for @see \Magento\Sales\Block\Adminhtml\Order\Invoice\View
 */
class Interceptor extends \Magento\Sales\Block\Adminhtml\Order\Invoice\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Widget\Context $context, \Magento\Backend\Model\Auth\Session $backendSession, \Magento\Framework\Registry $registry, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $backendSession, $registry, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrintUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPrintUrl');
        return $pluginInfo ? $this->___callPlugins('getPrintUrl', func_get_args(), $pluginInfo) : parent::getPrintUrl();
    }
}
