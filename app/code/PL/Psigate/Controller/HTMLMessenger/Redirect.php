<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Controller\HTMLMessenger;

class Redirect extends \PL\Psigate\Controller\HTMLMessenger
{
    protected $order;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \PL\Psigate\Helper\Data $psigateHelper,
        \PL\Psigate\Logger\Logger $plLogger
    ) {
        parent::__construct(
            $context,
            $orderFactory,
            $checkoutSession,
            $storeManager,
            $psigateHelper,
            $plLogger
        );
    }

    public function execute()
    {
        if ($this->checkoutSession->getLastRealOrderId()) {
            $this->_view->loadLayout();
            $this->_view->getLayout()->initMessages();
            $this->_view->renderLayout();
        }

    }

    /**
     * Get order object
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder()
    {
        if (!$this->order) {
            $incrementId = $this->checkoutSession->getLastRealOrderId();
            $this->order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        }
        return $this->order;
    }
}
