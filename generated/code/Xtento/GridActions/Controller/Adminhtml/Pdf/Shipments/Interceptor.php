<?php
namespace Xtento\GridActions\Controller\Adminhtml\Pdf\Shipments;

/**
 * Interceptor class for @see \Xtento\GridActions\Controller\Adminhtml\Pdf\Shipments
 */
class Interceptor extends \Xtento\GridActions\Controller\Adminhtml\Pdf\Shipments implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Sales\Model\Order\Pdf\Shipment $shipment, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory, \Xtento\GridActions\Ui\Component\MassAction\CustomFilter $customFilter, \Xtento\XtCore\Helper\Utils $utilsHelper)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory, $dateTime, $fileFactory, $shipment, $shipmentCollectionFactory, $customFilter, $utilsHelper);
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
