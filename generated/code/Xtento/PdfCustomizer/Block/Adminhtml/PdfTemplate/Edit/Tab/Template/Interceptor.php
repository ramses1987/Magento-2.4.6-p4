<?php
namespace Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Template;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Template
 */
class Interceptor extends \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Template implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Backend\Model\UrlInterface $buttonsVariable, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $buttonsVariable, $data);
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
