<?php
namespace Xtento\PdfCustomizer\Model\Template\Processor;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Model\Template\Processor
 */
class Interceptor extends \Xtento\PdfCustomizer\Model\Template\Processor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\View\DesignInterface $design, \Magento\Framework\Registry $registry, \Magento\Store\Model\App\Emulation $appEmulation, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Email\Model\Template\Config $emailConfig, \Magento\Email\Model\TemplateFactory $templateFactory, \Magento\Framework\Filter\FilterManager $filterManager, \Magento\Framework\UrlInterface $urlModel, \Magento\Email\Model\Template\FilterFactory $filterFactory, \Xtento\PdfCustomizer\Model\Email\Template\CustomFilterFactory $customFilterFactory, \Xtento\XtCore\Helper\Utils $utilsHelper, array $data = [], $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $design, $registry, $appEmulation, $storeManager, $assetRepo, $filesystem, $scopeConfig, $emailConfig, $templateFactory, $filterManager, $urlModel, $filterFactory, $customFilterFactory, $utilsHelper, $data, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(\Magento\Store\Model\Store $store, $route = '', $params = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        return $pluginInfo ? $this->___callPlugins('getUrl', func_get_args(), $pluginInfo) : parent::getUrl($store, $route, $params);
    }
}
