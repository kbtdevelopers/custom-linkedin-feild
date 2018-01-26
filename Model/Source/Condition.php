<?php
/**
 * Created by PhpStorm.
 * User: kavindu
 */


namespace RedboxDigital\Linkedin\Model\Source;

class Condition implements \Magento\Framework\Option\ArrayInterface
{

    const INVISIBLE = 0;
    const OPTIONAL = 1;
    const REQUIRED = 2;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::INVISIBLE, 'label' => __('Invisible')),
            array('value' => self::OPTIONAL, 'label' => __('Optional')),
            array('value' => self::REQUIRED, 'label' => __('Required'))
        );
    }

}
