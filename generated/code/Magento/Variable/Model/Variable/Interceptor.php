<?php
namespace Magento\Variable\Model\Variable;

/**
 * Interceptor class for @see \Magento\Variable\Model\Variable
 */
class Interceptor extends \Magento\Variable\Model\Variable implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Escaper $escaper, \Magento\Variable\Model\ResourceModel\Variable $resource, ?\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [], ?\Magento\Framework\Validator\HTML\WYSIWYGValidatorInterface $wysiwygValidator = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $escaper, $resource, $resourceCollection, $data, $wysiwygValidator);
    }

    /**
     * {@inheritdoc}
     */
    public function getVariablesOptionArray($withGroup = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getVariablesOptionArray');
        return $pluginInfo ? $this->___callPlugins('getVariablesOptionArray', func_get_args(), $pluginInfo) : parent::getVariablesOptionArray($withGroup);
    }
}
