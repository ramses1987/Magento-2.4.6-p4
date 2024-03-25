<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Variable\Source;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Variable\Source
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Variable\Source implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Email\Model\Template\Config $_emailConfig, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Xtento\PdfCustomizer\Helper\Variable\DefaultVariables $_defaultVariablesHelper, \Magento\Framework\Api\SearchCriteriaBuilder $_criteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Email\Model\BackendTemplateFactory $backendTemplateFactory, \Xtento\PdfCustomizer\Model\PdfTemplateRepository $templateRepository, \Xtento\PdfCustomizer\Helper\Variable\Custom\SalesCollect $taxCustom, \Xtento\PdfCustomizer\Helper\Variable\Processors\Pdf $pdfProcessor)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $_emailConfig, $resultJsonFactory, $_defaultVariablesHelper, $_criteriaBuilder, $filterBuilder, $backendTemplateFactory, $templateRepository, $taxCustom, $pdfProcessor);
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
