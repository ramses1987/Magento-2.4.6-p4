<?php

namespace Sm\AutoCompleteSearch\Model;

use \Sm\AutoCompleteSearch\Helper\Data as HelperData;
use \Sm\AutoCompleteSearch\Model\SearchFactory;

/**
 * Search class returns needed search data
 */
class Search
{
    /**
     * @var \Sm\AutoCompleteSearch\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Sm\AutoCompleteSearch\Model\SearchFactory
     */
    protected $searchFactory;

    /**
     * Search constructor.
     *
     * @param HelperData $helperData
     * @param \Sm\AutoCompleteSearch\Model\SearchFactory $searchFactory
     */
    public function __construct(
        HelperData $helperData,
        SearchFactory $searchFactory
    ) {
        $this->helperData    = $helperData;
        $this->searchFactory = $searchFactory;
    }

    /**
     * Retrieve suggested, product data
     *
     * @return array
     */
    public function getData()
    {
        $data               = [];
        $autocompleteFields = $this->helperData->getAutocompleteFieldsAsArray();

        foreach ($autocompleteFields as $field) {
            $data[] = $this->searchFactory->create($field)->getResponseData();
        }

        return $data;
    }
}
