<?php
namespace Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Preview;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Preview
 */
class Interceptor extends \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Preview implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Backend\Model\UrlInterface $url, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $url, $data);
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
