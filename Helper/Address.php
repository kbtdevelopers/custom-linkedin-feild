<?php
/**
 * Created by PhpStorm.
 * User: kavindu
 */

namespace RedboxDigital\Linkedin\Helper;

use RedboxDigital\Linkedin\Model\Source\Condition;

/**
 * Customer address helper
 */
class Address extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_CONDITION = 'redboxdigital_linkedin_config/general/condition';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * Check linkedin feild required or not
     *
     * @return boolean
     */
    public function isLinkedinFeildRequired()
    {

        $fieldClass = "";
        $configStatus = $this->scopeConfig->getValue(self::XML_PATH_CONDITION);
        $isRequiredConfig = 1;

        switch ($configStatus) {
            case Condition::INVISIBLE :
                $isRequiredConfig = 0;
                break;
            case Condition::OPTIONAL :
                $isRequiredConfig = 0;
                break;
            case Condition::REQUIRED :
                $isRequiredConfig = 1;
                break;
        }

        if($isRequiredConfig == 1) {
            $fieldClass = " required ";
        }
        return $fieldClass;
    }

    /**
     * Check linkedin feild visibile
     *
     * @return boolean
     */
    public function isLinkedinFeildInvisible()
    {

        $fieldClass = "";
        $configStatus = $this->scopeConfig->getValue(self::XML_PATH_CONDITION);
        $isVisibleConfig = 1;

        switch ($configStatus) {
            case Condition::INVISIBLE :
                $isVisibleConfig = 0;
                break;
            case Condition::OPTIONAL :
                $isVisibleConfig = 1;
                break;
            case Condition::REQUIRED :
                $isVisibleConfig = 1;
                break;
        }

        return $isVisibleConfig;
    }
}
