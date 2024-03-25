<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \PL\Psigate\Logger\Logger
     */
    protected $plLogger;

    protected $json;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \PL\Psigate\Logger\Logger $plLogger
    ) {
        parent::__construct($context);
        $this->plLogger = $plLogger;
    }

    /**
     * @param $text
     * @return \Magento\Framework\Phrase
     */
    public function wrapGatewayError($text)
    {
        return __('Gateway error: %1', $text);
    }
}
