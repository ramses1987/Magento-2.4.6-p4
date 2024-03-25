<?php
namespace MageBig\OrderComment\Plugin\Model\Order\Pdf\Invoice;

/**
 * Interceptor class for @see \MageBig\OrderComment\Plugin\Model\Order\Pdf\Invoice
 */
class Interceptor extends \MageBig\OrderComment\Plugin\Model\Order\Pdf\Invoice implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Payment\Helper\Data $paymentData, \Magento\Framework\Stdlib\StringUtils $string, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Filesystem $filesystem, \Magento\Sales\Model\Order\Pdf\Config $pdfConfig, \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory, \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Sales\Model\Order\Address\Renderer $addressRenderer, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Store\Model\App\Emulation $appEmulation, \MageBig\OrderComment\Model\AdditionalConfigVars $configVars, array $data = [])
    {
        $this->___init();
        parent::__construct($paymentData, $string, $scopeConfig, $filesystem, $pdfConfig, $pdfTotalFactory, $pdfItemsFactory, $localeDate, $inlineTranslation, $addressRenderer, $storeManager, $appEmulation, $configVars, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getPdf($invoices = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPdf');
        return $pluginInfo ? $this->___callPlugins('getPdf', func_get_args(), $pluginInfo) : parent::getPdf($invoices);
    }
}