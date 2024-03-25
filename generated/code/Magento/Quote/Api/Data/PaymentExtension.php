<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\PaymentInterface
 */
class PaymentExtension extends \Magento\Framework\Api\AbstractSimpleObject implements PaymentExtensionInterface
{
    /**
     * @return string[]|null
     */
    public function getAgreementIds()
    {
        return $this->_get('agreement_ids');
    }

    /**
     * @param string[] $agreementIds
     * @return $this
     */
    public function setAgreementIds($agreementIds)
    {
        $this->setData('agreement_ids', $agreementIds);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment()
    {
        return $this->_get('comment');
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->setData('comment', $comment);
        return $this;
    }
}
