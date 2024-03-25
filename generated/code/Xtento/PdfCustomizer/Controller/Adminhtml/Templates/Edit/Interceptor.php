<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Edit;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Edit
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Xtento\PdfCustomizer\Model\PdfTemplateRepository $templateRepository, \Xtento\PdfCustomizer\Model\PdfTemplateFactory $pdfTemplateFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $templateRepository, $pdfTemplateFactory);
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
