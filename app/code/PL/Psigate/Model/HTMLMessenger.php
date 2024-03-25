<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
 namespace PL\Psigate\Model;

 class HTMLMessenger extends \Magento\Payment\Model\Method\AbstractMethod
 {
     const METHOD_CODE = 'psigate_htmlmessenger';

     protected $_code = self::METHOD_CODE;

     protected $_infoBlockType = 'PL\Psigate\Block\Info\HTMLMessenger';

     /**
      * @var bool
      */
     protected $_canAuthorize = true;


     protected $_canCapture = true;

     /**
      * @var bool
      */
     protected $_canRefund = false;

     /**
      * @var bool
      */
     protected $_canRefundInvoicePartial = true;

     /**
      * @var bool
      */
     protected $_canUseInternal = false;

     /**
      * @var bool
      */
     protected $_isInitializeNeeded = true;

     /**
      * @var
      */
     protected $encryptor;

     /**
      * @var
      */
     protected $request;

     /**
      * @var
      */
     protected $urlBuilder;

     /**
      * @var
      */
     protected $plLogger;

     /**
      * @var
      */
     protected $jsonHelper;

     /**
      * @var
      */
     protected $orderSender;

     /**
      * @var
      */
     protected $invoiceSender;

     protected $checkoutSession;

     /**
      * HTMLMessenger constructor.
      * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
      * @param \Magento\Framework\App\RequestInterface $request
      * @param \Magento\Framework\UrlInterface $urlBuilder
      * @param \PL\Psigate\Helper\Data $psigateHelper
      * @param \PL\Psigate\Logger\Logger $plLogger
      * @param \Magento\Framework\Json\Helper\Data $jsonHelper
      * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
      * @param \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender
      * @param \Magento\Framework\Model\Context $context
      * @param \Magento\Framework\Registry $registry
      * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
      * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
      * @param \Magento\Payment\Helper\Data $paymentData
      * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
      * @param \Magento\Payment\Model\Method\Logger $logger
      * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
      * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
      * @param array $data
      */
     public function __construct(
         \Magento\Framework\Encryption\EncryptorInterface $encryptor,
         \Magento\Framework\App\RequestInterface $request,
         \Magento\Framework\UrlInterface $urlBuilder,
         \PL\Psigate\Helper\Data $psigateHelper,
         \PL\Psigate\Logger\Logger $plLogger,
         \Magento\Framework\Json\Helper\Data $jsonHelper,
         \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
         \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
         \Magento\Checkout\Model\Session $checkoutSession,
         \Magento\Framework\Model\Context $context,
         \Magento\Framework\Registry $registry,
         \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
         \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
         \Magento\Payment\Helper\Data $paymentData,
         \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
         \Magento\Payment\Model\Method\Logger $logger,
         \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
         \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
         array $data = []
     ) {
         parent::__construct(
             $context,
             $registry,
             $extensionFactory,
             $customAttributeFactory,
             $paymentData,
             $scopeConfig,
             $logger,
             $resource,
             $resourceCollection,
             $data
         );
         $this->urlBuilder = $urlBuilder;
         $this->psigateHelper = $psigateHelper;
         $this->plLogger = $plLogger;
         $this->request = $request;
         $this->encryptor = $encryptor;
         $this->jsonHelper = $jsonHelper;
         $this->orderSender = $orderSender;
         $this->invoiceSender = $invoiceSender;
         $this->checkoutSession = $checkoutSession;
     }

     /**
      * @return $this
      * @throws \Magento\Framework\Exception\LocalizedException
      */
     public function validate()
     {
         /** @noinspection PhpDeprecationInspection */
         parent::validate();
         $paymentInfo = $this->getInfoInstance();
         /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
         if ($paymentInfo instanceof \Magento\Sales\Model\Order\Payment) {
             $paymentInfo->getOrder()->getBaseCurrencyCode();
         } else {
             $paymentInfo->getQuote()->getBaseCurrencyCode();
         }
         return $this;
     }

     /**
      * @return string
      */
     public function getGatewayUrl()
     {
        if ($this->getConfigData('testmode')) {
           return 'https://stagingcheckout.psigate.com/HTMLPost/HTMLMessenger';
        }
         return 'https://checkout.psigate.com/HTMLPost/HTMLMessenger';
     }

     /**
      * @return string
      */
     public function getCheckoutRedirectUrl()
     {
         return $this->urlBuilder->getUrl(
             'psigate/htmlmessenger/redirect',
             ['_secure' => $this->request->isSecure()]
         );
     }

     /**
      * @return string
      */
     public function getNoThanksUrl()
     {
         return $this->urlBuilder->getUrl(
             'psigate/htmlmessenger/reject',
             ['_secure' => $this->request->isSecure()]
         );
     }

     /**
      * @return string
      */
     public function getThanksUrl()
     {
         return $this->urlBuilder->getUrl(
             'psigate/htmlmessenger/accept',
             ['_secure' => $this->request->isSecure()]
         );
     }

     /**
      * @param \Magento\Sales\Model\Order $order
      * @return mixed
      */
     function getGrandTotal(\Magento\Sales\Model\Order $order)
     {
         $amount = sprintf("%.2F",$order->getBaseGrandTotal());
         return $amount;
     }

     public function genarateUid(\Magento\Sales\Model\Order $order)
     {
        return $order->getIncrementId().'-'.time();
     }

     public function getUid($str = null)
     {
         if (!empty($str)) {
             $data = explode("-",$str);
             return $data[0];
         }
     }

     /**
      * @param string $paymentAction
      * @param object $stateObject
      * @throws \Magento\Framework\Exception\LocalizedException
      */
     public function initialize($paymentAction, $stateObject)
     {
         if ($paymentAction == 'order') {
             $order = $this->getInfoInstance()->getOrder();
             $order->setCustomerNoteNotify(false);
             $order->setCanSendNewEmailFlag(false);
             $stateObject->setIsNotified(false);
             $stateObject->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
             $stateObject->setStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
         }
     }

     /**
      * @return mixed
      * @throws \Magento\Framework\Exception\LocalizedException
      */
     public function getFormFields()
     {
         $paymentInfo = $this->getInfoInstance();
         $order = $paymentInfo->getOrder();
         $billing = $order->getBillingAddress();
         $shipping = $order->getShippingAddress();
         if ($order->getIsVirtual()) {
             $shipping = $order->getBillingAddress();
         }
         $formFields['MerchantID'] =  $this->getConfigData('merchant_id');
         $formFields['PaymentType'] = 'CC';
         $formFields['OrderID'] = $this->genarateUid($order);
         $formFields['SubTotal'] = $this->getGrandTotal($order);
         if ($this->getConfigData('testmode')) {
             $formFields['TestResult'] = 'A';
         }
         // Billing Information
         $formFields['Bname'] = $billing->getName();
         $formFields['Bcompany'] = $billing->getCompany();
         $formFields['Baddress1'] =  $billing->getStreetLine(1);
         $formFields['Bcity'] = $billing->getCity();
         $formFields['Bprovince'] = $billing->getRegion();
         $formFields['Bcountry'] = $billing->getCountryId();
         $formFields['Bpostalcode'] = $billing->getPostcode();

         // Shipping Information
         $formFields['Sname'] = $shipping->getName();
         $formFields['Scompany'] = $shipping->getCompany();
         $formFields['Saddress1'] =  $shipping->getStreetLine(1);
         $formFields['Scity'] = $shipping->getCity();
         $formFields['Sprovince'] = $shipping->getRegion();
         $formFields['Scountry'] = $shipping->getCountryId();
         $formFields['Spostalcode'] = $shipping->getPostcode();
         $formFields['Email'] = $order->getCustomerEmail();
         $formFields['Phone'] = $billing->getTelephone();

         $formFields['CardAction'] = 0;
         $formFields['CustomerLanguage'] =  'EN_CA';
         $formFields['ThanksURL']= $this->getThanksUrl();
         $formFields['NoThanksURL'] = $this->getNoThanksUrl();
         if ($this->getConfigData('debug')) {
             $this->plLogger->debug('formFields: '. print_r($formFields, 1));
         }
         return $formFields;
     }

     /**
      * @param $order
      * @param array $response
      */
     public function acceptTransaction(\Magento\Sales\Model\Order $order, $response = [])
     {
         $this->checkOrderStatus($order);
         if ($order->getId()) {
             $additionalData = $this->jsonHelper->jsonEncode($response);
             $order->getPayment()->setTransactionId($response['TransRefNumber']);
             $order->getPayment()->setLastTransId($response['TransRefNumber']);
             $order->getPayment()->setAdditionalInformation('payment_additional_info', $additionalData);
             $order->setStatus(\Magento\Sales\Model\Order::STATE_PROCESSING);
             $note = __('Approved. Transaction ID: "%1"', $response['TransRefNumber']);
             $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING);
             $order->addStatusHistoryComment($note);
             $order->setTotalpaid($order->getBaseGrandTotal());
             $this->orderSender->send($order);
             if (!$order->hasInvoices() && $order->canInvoice()) {
                 $invoice = $order->prepareInvoice();
                 if ($invoice->getTotalQty() > 0) {
                     $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                     $invoice->setTransactionId($order->getPayment()->getTransactionId());
                     $invoice->register();
                     $invoice->addComment(__('Automatic invoice.'), false);
                     $invoice->save();
                     $this->invoiceSender->send($invoice);
                 }
             }
             $order->save();
         }
     }

     /**
      * @param $order
      * @param array $response
      */
     public function rejectTransaction(\Magento\Sales\Model\Order $order, $response = [])
     {
         $this->checkOrderStatus($order);

         if ($order->getId()) {
             $note = 'Your order has been cancelled';
             if (isset($response['TransRefNumber'])) {
                 $additionalData = $this->jsonHelper->jsonEncode($response);
                 $note= $response['Approved'];
                 $note.= __('. Transaction ID: "%1"', $response['TransRefNumber']);
                 $order->getPayment()->setAdditionalInformation('payment_additional_info', $additionalData);
             }
             if (isset($response['ErrMsg']) && $response['ErrMsg']!="") {
                 $note= $response['ErrMsg'];
             }
             if ($order->getState()!= \Magento\Sales\Model\Order::STATE_CANCELED) {
                 $order->registerCancellation($note)->save();
             }
             $this->checkoutSession->restoreQuote();
         }
     }


     public function checkOrderStatus($order)
     {
         if ($order->getId()) {
             $state = $order->getState();
             switch ($state) {
                 case \Magento\Sales\Model\Order::STATE_HOLDED:
                 case \Magento\Sales\Model\Order::STATE_CANCELED:
                 case \Magento\Sales\Model\Order::STATE_CLOSED:
                 case \Magento\Sales\Model\Order::STATE_COMPLETE:
                     break;
             }
         }
     }
 }
