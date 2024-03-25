<?php
namespace Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction\PrintpdfCreditmemo;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction\PrintpdfCreditmemo
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\Adminhtml\Order\Massaction\PrintpdfCreditmemo implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Xtento\PdfCustomizer\Helper\GeneratePdf $generatePdfHelper, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $childCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $fileFactory, $resultForwardFactory, $generatePdfHelper, $collectionFactory, $childCollectionFactory);
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
