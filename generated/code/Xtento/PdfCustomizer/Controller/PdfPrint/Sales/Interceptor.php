<?php
namespace Xtento\PdfCustomizer\Controller\PdfPrint\Sales;

/**
 * Interceptor class for @see \Xtento\PdfCustomizer\Controller\PdfPrint\Sales
 */
class Interceptor extends \Xtento\PdfCustomizer\Controller\PdfPrint\Sales implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Xtento\PdfCustomizer\Helper\GeneratePdf $generatePdfHelper, \Magento\Customer\Model\Session $customerSession, \Xtento\PdfCustomizer\Api\TemplatesRepositoryInterface $templatesRepository, \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $generatePdfHelper, $customerSession, $templatesRepository, $criteriaBuilder, $filterBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
