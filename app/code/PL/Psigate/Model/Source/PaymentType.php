<?php
/**
 * PL Development.
 *
 * @category    PL
 * @author      Linh Pham <plinh5@gmail.com>
 * @copyright   Copyright (c) 2016 PL Development. (http://www.polacin.com)
 */
namespace PL\Psigate\Model\Source;

class PaymentType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Possible actions on order place
     *
     * @return array
     */
    public function toOptionArray()
    {
        /** @noinspection PhpDeprecationInspection */
        return [
            [
                'value' => 'CC',
                'label' => __('Credit Cards'),
            ],
            [
                'value' => 'DB',
                'label' => __('Debit/Interac Online'),
            ]
        ];
    }
}
