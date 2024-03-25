<?php
namespace Xtento\XtCore\Model\System\Config\Backend\Server;

/**
 * Interceptor class for @see \Xtento\XtCore\Model\System\Config\Backend\Server
 */
class Interceptor extends \Xtento\XtCore\Model\System\Config\Backend\Server implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Xtento\XtCore\Helper\Server $serverHelper, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Framework\Module\ModuleListInterface $moduleList, ?\Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, ?\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $registry, $request, $config, $cacheTypeList, $serverHelper, $urlBuilder, $moduleList, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        return $pluginInfo ? $this->___callPlugins('afterSave', func_get_args(), $pluginInfo) : parent::afterSave();
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        return $pluginInfo ? $this->___callPlugins('save', func_get_args(), $pluginInfo) : parent::save();
    }
}
