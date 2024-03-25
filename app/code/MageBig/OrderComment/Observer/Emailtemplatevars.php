<?php
namespace MageBig\OrderComment\Observer;

/**
 * Class Emailtemplatevars
 * @package MageBig\OrderComment\Observer
 */
class Emailtemplatevars implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $transport = $observer->getEvent()->getTransport();
        if ($transport->getOrder() != null) {
            $transport['magebig_order_comment'] = $transport->getOrder()->getMagebigOrderComment();
        }
    }
}
