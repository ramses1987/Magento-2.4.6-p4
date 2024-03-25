<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Duplicate;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Duplicate
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\Duplicate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Xtento\PdfCustomizer\Controller\Adminhtml\Templates\PdfDataProcessor $dataProcessor, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor, \Xtento\PdfCustomizer\Model\PdfTemplateRepository $templateRepository, \Xtento\PdfCustomizer\Model\PdfTemplateFactory $pdfTemplateFactory)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $dataPersistor, $templateRepository, $pdfTemplateFactory);
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
