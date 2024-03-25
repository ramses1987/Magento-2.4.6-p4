<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Block\Info;

class HTMLMessenger extends \Magento\Payment\Block\Info
{
    protected $_template = 'PL_Psigate::info/htmlmessenger.phtml';

    /**
     * @var \PL\Psigate\Helper\Data
     */
    protected $psigateHelper;

    /**
     * Hosted constructor.
     * @param \PL\Psigate\Helper\Data $psigateHelper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \PL\Psigate\Helper\Data $psigateHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->psigateHelper = $psigateHelper;
    }
}