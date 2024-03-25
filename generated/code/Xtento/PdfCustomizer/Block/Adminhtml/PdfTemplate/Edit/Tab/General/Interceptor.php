<?php
namespace Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\General;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\General
 */
class Interceptor extends \Xtento\PdfCustomizer\Block\Adminhtml\PdfTemplate\Edit\Tab\General implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Xtento\PdfCustomizer\Model\Source\TemplateType $templateType, \Magento\Config\Model\Config\Source\Yesno $yesNo, \Xtento\PdfCustomizer\Model\Source\Barcode $barcodeTypes, \Magento\Store\Model\System\Store $systemStore, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $templateType, $yesNo, $barcodeTypes, $systemStore, $data);
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
