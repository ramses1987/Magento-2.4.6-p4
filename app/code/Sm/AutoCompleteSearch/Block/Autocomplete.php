<?php

namespace Sm\AutoCompleteSearch\Block;

/**
 * Autocomplete class used for transport config data
 */
class Autocomplete extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Sm\AutoCompleteSearch\Helper\Data
     */
    protected $helperData;

    /**
     * Autocomplete constructor.
     *
     * @param \Sm\AutoCompleteSearch\Helper\Data $helperData
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Sm\AutoCompleteSearch\Helper\Data $helperData,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {

        $this->helperData = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve search delay in milliseconds (500 by default)
     *
     * @return int
     */
    public function getSearchDelay()
    {
        return $this->helperData->getSearchDelay();
    }
    public function getSearchEnable()
    {
        return $this->helperData->getSearchEnable();
    }
    /**
     * Retrieve search action url
     *
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->getUrl("sm_autocompletesearch/ajax/index");
    }
}
