<?php

namespace MageBig\OrderComment\Plugin\Model\Order\Pdf;

use MageBig\OrderComment\Model\AdditionalConfigVars;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Payment\Helper\Data;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Sales\Model\Order\Pdf\ItemsFactory;
use Magento\Sales\Model\Order\Pdf\Total\Factory;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;

class Invoice extends \Magento\Sales\Model\Order\Pdf\Invoice
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var Emulation
     */
    private $appEmulation;

    /**
     * @var AdditionalConfigVars
     */
    public $configVars;

    /**
     * @param Data $paymentData
     * @param StringUtils $string
     * @param ScopeConfigInterface $scopeConfig
     * @param Filesystem $filesystem
     * @param Config $pdfConfig
     * @param Factory $pdfTotalFactory
     * @param ItemsFactory $pdfItemsFactory
     * @param TimezoneInterface $localeDate
     * @param StateInterface $inlineTranslation
     * @param Renderer $addressRenderer
     * @param StoreManagerInterface $storeManager
     * @param Emulation $appEmulation
     * @param AdditionalConfigVars $configVars
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Data $paymentData,
        StringUtils $string,
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        Config $pdfConfig,
        Factory $pdfTotalFactory,
        ItemsFactory $pdfItemsFactory,
        TimezoneInterface $localeDate,
        StateInterface $inlineTranslation,
        Renderer $addressRenderer,
        StoreManagerInterface $storeManager,
        Emulation $appEmulation,
        AdditionalConfigVars $configVars,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->appEmulation = $appEmulation;
        $this->configVars = $configVars;
        parent::__construct(
            $paymentData,
            $string,
            $scopeConfig,
            $filesystem,
            $pdfConfig,
            $pdfTotalFactory,
            $pdfItemsFactory,
            $localeDate,
            $inlineTranslation,
            $addressRenderer,
            $storeManager,
            $appEmulation,
            $data
        );
    }

    /**
     * Get Pdf
     *
     * @param $subject
     * @param $proceed
     * @param $invoices
     * @return \Zend_Pdf
     * @throws \Zend_Pdf_Exception
     */
    public function aroundGetPdf($subject, $proceed, $invoices)
    {
        if (!$this->configVars->isCommentPdf()) {
            return $proceed($invoices);
        }

        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                $this->appEmulation->startEnvironmentEmulation(
                    $invoice->getStoreId(),
                    \Magento\Framework\App\Area::AREA_FRONTEND,
                    true
                );
                $this->_storeManager->setCurrentStore($invoice->getStoreId());
            }
            $page = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );
            /* Add document text and number */
            $this->insertDocumentNumber($page, __('Invoice # ') . $invoice->getIncrementId());
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($invoice->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId()) {
                $this->appEmulation->stopEnvironmentEmulation();
            }

            $comment = $order->getMagebigOrderComment();
            if ($comment) {
                $comment = __('Order comment: ') . $comment;
                $this->insertComment($page, $comment);
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    /**
     * Insert Comment
     *
     * @param \Zend_Pdf_Page $page
     * @param string $text
     * @return void
     * @throws \Zend_Pdf_Exception
     */
    public function insertComment(\Zend_Pdf_Page $page, string $text)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->_setFontRegular($page, 10);
        $content = explode("\n", $text);
        $i = 0;

        foreach ($content as $txt) {
            $txt = wordwrap($txt, 120, "\n");
            $strs = explode("\n", $txt);
            foreach ($strs as $str) {
                if ($i == 0) {
                    $this->y -= 30;
                } else {
                    $this->y -= 15;
                }
                $page->drawText($str, 25, $this->y, 'UTF-8');
                $i++;
            }
        }
    }
}
