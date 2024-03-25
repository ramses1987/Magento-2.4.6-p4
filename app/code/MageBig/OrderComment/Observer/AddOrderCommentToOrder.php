<?php
namespace MageBig\OrderComment\Observer;

/**
 * Class AddOrderCommentToOrder
 * @package MageBig\OrderComment\Observer
 */
class AddOrderCommentToOrder implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setData('magebig_order_comment', $quote->getMagebigOrderComment());
    }
}
