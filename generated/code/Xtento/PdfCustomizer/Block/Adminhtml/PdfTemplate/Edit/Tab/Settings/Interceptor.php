<?php
namespace Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Settings;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Settings
 */
class Interceptor extends \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\Settings implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Xtento\PdfCustomizer\Model\Source\TemplatePaperOrientation $templatePaperOrientation, \Magento\Config\Model\Config\Source\Yesno $yesNo, \Xtento\PdfCustomizer\Model\Source\TemplatePaperForm $templatePaperFormat, \Magento\Store\Model\System\Store $systemStore, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $templatePaperOrientation, $yesNo, $templatePaperFormat, $systemStore, $data);
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
