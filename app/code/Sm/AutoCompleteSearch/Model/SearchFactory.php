<?php


namespace Sm\AutoCompleteSearch\Model;

use Magento\Framework\ObjectManagerInterface as ObjectManager;

/**
 * SearchFactory class for Search model
 */
class SearchFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * @var string
     */
    protected $map;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $map
     */
    public function __construct(
        ObjectManager $objectManager,
        array $map = []
    ) {
        $this->objectManager = $objectManager;
        $this->map           = $map;
    }

    /**
     *
     * @param string $param
     * @param array $arguments
     * @return \Sm\AutoCompleteSearch\Model\SearchInterface
     * @throws \UnexpectedValueException
     */
    public function create($param, array $arguments = [])
    {
        if (isset($this->map[$param])) {
            $instance = $this->objectManager->create($this->map[$param], $arguments);
        } else {
            $instance = $this->objectManager->create(
                '\Sm\AutoCompleteSearch\Model\Search\Suggested',
                $arguments
            );
        }

        if (!$instance instanceof \Sm\AutoCompleteSearch\Model\SearchInterface) {
            throw new \UnexpectedValueException(
                'Class ' . get_class(
                    $instance
                ) . ' should be an instance of \Sm\AutoCompleteSearch\Model\SearchInterface'
            );
        }

        return $instance;
    }
}
