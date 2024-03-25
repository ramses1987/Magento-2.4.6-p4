<?php
namespace Magento\InventoryAdminUi\Ui\Component\Listing\MassAction;

/**
 * Interceptor class for @see \Magento\InventoryAdminUi\Ui\Component\Listing\MassAction
 */
class Interceptor extends \Magento\InventoryAdminUi\Ui\Component\Listing\MassAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\AuthorizationInterface $authorization, \Magento\Framework\View\Element\UiComponent\ContextInterface $context, array $components = [], array $data = [])
    {
        $this->___init();
        parent::__construct($authorization, $context, $components, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare() : void
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepare');
        $pluginInfo ? $this->___callPlugins('prepare', func_get_args(), $pluginInfo) : parent::prepare();
    }
}
