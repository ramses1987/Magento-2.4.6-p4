<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Index;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Index
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Xtento\PdfCustomizer\Helper\Module $moduleHelper, \Xtento\PdfCustomizer\Model\Files\Synchronization $synchronization)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $resultPageFactory, $moduleHelper, $synchronization);
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
