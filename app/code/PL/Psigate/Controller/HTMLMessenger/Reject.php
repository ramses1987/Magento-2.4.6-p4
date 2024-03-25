<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Controller\HTMLMessenger;

class Reject extends \PL\Psigate\Controller\HTMLMessenger
{
    protected $htmlMessenger;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \PL\Psigate\Helper\Data $psigateHelper,
        \PL\Psigate\Logger\Logger $plLogger,
        \PL\Psigate\Model\HTMLMessenger $htmlMessenger
    ) {
        parent::__construct(
            $context,
            $orderFactory,
            $checkoutSession,
            $storeManager,
            $psigateHelper,
            $plLogger
        );
        $this->htmlMessenger = $htmlMessenger;
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($this->htmlMessenger->getConfigData('debug')) {
            $this->plLogger->debug('Reject Transaction Params: '. print_r($params, 1));
        }

        if(isset($params['ErrMsg']) && $params['ErrMsg']!="") {
            $order = $this->orderFactory->create()->loadByIncrementId(
                $this->checkoutSession->getLastRealOrderId()
            );
            $message = $params['ErrMsg'];
            $this->messageManager->addError(__($message));
            $this->htmlMessenger->rejectTransaction($order, $params);
            $this->_redirect('checkout/cart');
            return;
        }

        if (isset($params['OrderID'])) {
            $order = $this->orderFactory->create()->loadByIncrementId(
                $this->checkoutSession->getLastRealOrderId()
            );
            if (isset($params['Approved']) && $params['Approved'] == 'DECLINED') {
                $this->htmlMessenger->rejectTransaction($order, $params);
                $message = 'Transaction was declined';
                $this->messageManager->addError(__($message));
                $this->_redirect('checkout/cart');
                return;
            }
        }
    }
}