<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Variable\ProductCustom;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Variable\ProductCustom
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Variable\ProductCustom implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Email\Model\Template\Config $_emailConfig, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Xtento\PdfCustomizer\Model\PdfTemplateRepository $templateRepository, \Xtento\PdfCustomizer\Helper\Variable\DefaultVariables $_defaultVariablesHelper, \Magento\Framework\Api\SearchCriteriaBuilder $_criteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Xtento\PdfCustomizer\Helper\Variable\Custom\Product $customData, \Magento\Email\Model\BackendTemplateFactory $backendTemplateFactory, \Xtento\PdfCustomizer\Helper\Variable\Custom\SalesCollect $taxCustom)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $_emailConfig, $resultJsonFactory, $templateRepository, $_defaultVariablesHelper, $_criteriaBuilder, $filterBuilder, $customData, $backendTemplateFactory, $taxCustom);
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
