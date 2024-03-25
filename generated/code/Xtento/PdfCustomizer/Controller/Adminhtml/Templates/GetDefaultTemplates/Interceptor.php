<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Templates\GetDefaultTemplates;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\GetDefaultTemplates
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\GetDefaultTemplates implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory, \Xtento\PdfCustomizer\Model\Files\TemplateReader $templateReader)
    {
        $this->___init();
        parent::__construct($context, $registry, $jsonResultFactory, $templateReader);
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
