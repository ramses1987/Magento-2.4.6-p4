<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Variable\Standard;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Variable\Standard
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Variable\Standard implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Email\Model\Template\Config $_emailConfig, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Xtento\PdfCustomizer\Model\PdfTemplateRepository $templateRepository, \Xtento\PdfCustomizer\Helper\Variable\DefaultVariables $_defaultVariablesHelper, \Magento\Framework\Api\SearchCriteriaBuilder $_criteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Framework\Json\Helper\Data $jsonData, \Magento\Variable\Model\VariableFactory $variableModelFactory, \Magento\Email\Model\BackendTemplateFactory $backendTemplateFactory, \Xtento\PdfCustomizer\Helper\Variable\Custom\SalesCollect $taxCustom, \Xtento\XtCore\Helper\Utils $utilsHelper)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $_emailConfig, $resultJsonFactory, $templateRepository, $_defaultVariablesHelper, $_criteriaBuilder, $filterBuilder, $jsonData, $variableModelFactory, $backendTemplateFactory, $taxCustom, $utilsHelper);
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
