<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Order\Printpdf;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Order\Printpdf
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Order\Printpdf implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Xtento\PdfCustomizer\Helper\GeneratePdf $generatePdfHelper, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory, \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $creditmemoCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $generatePdfHelper, $collectionFactory, $invoiceCollectionFactory, $shipmentCollectionFactory, $creditmemoCollectionFactory);
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
