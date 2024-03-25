<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Model;

class XMLMessenger extends \Magento\Payment\Model\Method\Cc
{
    const METHOD_CODE = 'psigate_xmlmessenger';

    protected $_code = self::METHOD_CODE;

    protected $_isGateway = true;

    protected $_canCapture = true;

    protected $_canCapturePartial = false;

    protected $_canRefund = false;

    protected $_canVoid = false;

    protected $_minOrderTotal = 0;

    /**
     * @var \PL\Psigate\Logger\Logger
     */
    protected $plLogger;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var \PL\Psigate\Helper\Data
     */
    protected $psigateHelper;

    /**
     * XMLMessenger constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \PL\Psigate\Logger\Logger $plLogger
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \PL\Psigate\Helper\Data $psigateHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \PL\Psigate\Logger\Logger $plLogger,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \PL\Psigate\Helper\Data $psigateHelper,
        array $data = array()
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $moduleList,
            $localeDate,
            null,
            null,
            $data
        );
        $this->plLogger = $plLogger;
        $this->encryptor = $encryptor;
        $this->psigateHelper = $psigateHelper;
    }

    /**
     * @return string
     */
    public function getGatewayUrl()
    {
        if ($this->getConfigData('testmode')) {
            return 'https://realtimestaging.psigate.com/xml';
        }
        return 'https://secure.psigate.com:27934/Messenger/XMLMessenger';
    }

    /**
     * @return bool
     */
    public function enableSSL()
    {
        if ($this->getConfigData('ssl_enable')) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getPSiGateStoreId()
    {
        return $this->getConfigData('store_id');
    }

    /**
     * @return string
     */
    public function getPassphrase()
    {
        return $this->encryptor->decrypt(
            $this->getConfigData('passphrase')
        );
    }

    /**
     * @return int
     */
    public function getCardAction()
    {
        if ($this->getConfigData('payment_action') == 'authorize') {
            return 1;
        }
        return 0;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $errorMessage = false;
        $result = $this->postRequest($payment, $amount);
        if ($result->Approved == 'APPROVED') {
            $payment
                ->setTransactionId($result->TransRefNumber)
                ->setLastTransId($result->TransRefNumber)
                ->setParentTransactionId($result->TransRefNumber)
                ->setIsTransactionClosed(0);
        } else {
            $errorMessage = $this->psigateHelper->wrapGatewayError($result->ErrMsg);
        }

        if ($errorMessage) {
            throw new \Magento\Framework\Exception\LocalizedException($errorMessage);
        }
        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $errorMessage = false;
        $result = $this->postRequest($payment, $amount);
        if ($result->Approved == 'APPROVED') {
            $payment
                ->setTransactionId($result->TransRefNumber)
                ->setLastTransId($result->TransRefNumber)
                ->setParentTransactionId($result->TransRefNumber)
                ->setIsTransactionClosed(0);
        } else {
            $errorMessage = $this->psigateHelper->wrapGatewayError($result->ErrMsg);
        }

        if ($errorMessage) {
            throw new \Magento\Framework\Exception\LocalizedException($errorMessage);
        }
        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param $amount
     * @return \SimpleXMLElement
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function postRequest(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $order = $payment->getOrder();
        $billing = $order->getBillingAddress();
        $shipping = $order->getShippingAddress();
        if ($order->getIsVirtual()) {
            $shipping = $order->getBillingAddress();
        }
        $xmlReq = new \SimpleXMLElement('<Order></Order>');
        $xmlReq->addChild('StoreID', $this->getPSiGateStoreId());
        $xmlReq->addChild('Passphrase', $this->getPassphrase());
        $xmlReq->addChild('OrderID', $payment->getOrder()->getIncrementId());
        $xmlReq->addChild('Subtotal', $amount);
        $xmlReq->addChild('PaymentType', 'CC');
        $xmlReq->addChild('CardAction', $this->getCardAction());
        $xmlReq->addChild('CardNumber', $payment->getCcNumber());
        $xmlReq->addChild('CardExpMonth', sprintf('%02d', $payment->getCcExpMonth()));
        $xmlReq->addChild('CardExpYear', substr($payment->getCcExpYear(), 2, 2));
        $xmlReq->addChild('CardIDNumber', $payment->getCcCid());
        $xmlReq->addChild('Bname', htmlentities($billing->getName()));
        $xmlReq->addChild('Baddress1', htmlentities($billing->getStreetLine(1)));
        $xmlReq->addChild('Bcity', htmlentities($billing->getCity()));
        $xmlReq->addChild('Bprovince', htmlentities($billing->getRegion()));
        $xmlReq->addChild('Bpostalcode', htmlentities($billing->getPostcode()));
        $xmlReq->addChild('Bcountry', htmlentities($billing->getCountryId()));
        $xmlReq->addChild('Sname', htmlentities($shipping->getName()));
        $xmlReq->addChild('Saddress1', htmlentities($shipping->getStreetLine(1)));
        $xmlReq->addChild('Scity', htmlentities($shipping->getCity()));
        $xmlReq->addChild('Sprovince', htmlentities($shipping->getRegion()));
        $xmlReq->addChild('Spostalcode', htmlentities($shipping->getPostcode()));
        $xmlReq->addChild('Scountry', htmlentities($shipping->getCountryId()));
        $xmlReq->addChild('Phone', $billing->getTelephone());
        $xmlReq->addChild('Email', $order->getCustomerEmail());
        $xmlReq->asXML();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getGatewayUrl());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlReq->asXML());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->enableSSL());
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
			if ($this->getConfigData('debug')) {
                $this->plLogger->debug(curl_error($ch));
            }
            throw new \Magento\Framework\Exception\LocalizedException(__(curl_error($ch)));
        }
        curl_close($ch);
        $response = simplexml_load_string($result);
        if ($this->getConfigData('debug')) {
            $this->plLogger->debug(print_r($response,1));
        }
        return $response;
    }
}