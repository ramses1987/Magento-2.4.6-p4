<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Controller\HTMLMessenger;

class Accept extends \PL\Psigate\Controller\HTMLMessenger
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
            $this->plLogger->debug('Accept Transaction Params: '. print_r($params, 1));
        }
        if ($params['Approved'] == 'APPROVED') {
            $order = $this->orderFactory
                ->create()
                ->loadByIncrementId(
                    $this->htmlMessenger->getUid($params['OrderID'])
                );
            $this->htmlMessenger->acceptTransaction($order, $params);
            $this->_redirect('checkout/onepage/success');
        } else {
            $this->messageManager->addError(__('You have cancelled the order. Please try again'));
            $this->_redirect('checkout/cart');
        }
    }
}