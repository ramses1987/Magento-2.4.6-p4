<?php
namespace Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit\Tab\Form;

/**
 * Interceptor class for @see \Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit\Tab\Form
 */
class Interceptor extends \Sm\MegaMenu\Block\Adminhtml\MenuItems\Edit\Tab\Form implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\DataObject $dataObject, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Store\Model\System\Store $systemStore, \Magento\Framework\View\Layout $layout, \Magento\Backend\Helper\Data $backendData, \Magento\Framework\ObjectManagerInterface $objectManagerInterface, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $dataObject, $formFactory, $systemStore, $layout, $backendData, $objectManagerInterface, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getForm');
        return $pluginInfo ? $this->___callPlugins('getForm', func_get_args(), $pluginInfo) : parent::getForm();
    }
}
