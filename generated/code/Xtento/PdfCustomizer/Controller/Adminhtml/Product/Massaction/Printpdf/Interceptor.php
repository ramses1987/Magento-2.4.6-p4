<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Product\Massaction\Printpdf;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Product\Massaction\Printpdf
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Product\Massaction\Printpdf implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Xtento\PdfCustomizer\Helper\GeneratePdf $generatePdfHelper, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Xtento\PdfCustomizer\Model\PdfTemplateFactory $pdfTemplateFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $fileFactory, $generatePdfHelper, $collectionFactory, $productCollectionFactory, $pdfTemplateFactory);
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
