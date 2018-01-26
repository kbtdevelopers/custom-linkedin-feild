<?php
/**
 * Created by PhpStorm.
 * User: kavindu
 */


namespace RedboxDigital\Linkedin\Observer\Admin;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use RedboxDigital\Linkedin\Model\Source\Condition;

/**
 * Class ConfigObserver
 * @package RedboxDigital\Linkedin\Observer\Admin
 */
class ConfigObserver implements ObserverInterface
{

    const XML_PATH_CONDITION = 'redboxdigital_linkedin_config/general/condition';

    /**
     * Attribute Factory
     *
     * @var \Magento\Customer\Model\AttributeFactory
     */
    protected $attrFactory;

    /**
     * Website Factory
     *
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $websiteFactory;

    /**
     * Scope Config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * ConfigObserver constructor.
     * @param \Magento\Customer\Model\AttributeFactory $attrFactory
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Customer\Model\AttributeFactory $attrFactory,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->attrFactory = $attrFactory;
        $this->websiteFactory = $websiteFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Observer for config change
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {

        $configStatus = $this->scopeConfig->getValue(self::XML_PATH_CONDITION);
        $isRequiredConfig = 1;
        $isVisibleConfig = 1;

        switch ($configStatus) {
            case Condition::INVISIBLE :
                $isRequiredConfig = 0;
                $isVisibleConfig = 0;
                break;
            case Condition::OPTIONAL :
                $isRequiredConfig = 0;
                $isVisibleConfig = 1;
                break;
            case Condition::REQUIRED :
                $isRequiredConfig = 1;
                $isVisibleConfig = 1;
                break;
        }

        /* @var $attributeObject \Magento\Customer\Model\Attribute */
        $attributeId = 'linkedin_profile';
        $attributeField = 'attribute_code';
        $attributeObject = $this->initAttribute();
        $attributeObject->load($attributeId, $attributeField);

        $isRequiredValue =  $attributeObject->getIsRequired();
        $isVisibleValue =  $attributeObject->getIsVisible();

        /** Check customer attribute need to update */
        if (!($isRequiredValue == $isRequiredConfig && $isVisibleValue == $isVisibleConfig)) {
            $data['is_required'] = $isRequiredConfig;
            $data['is_visible'] = $isVisibleConfig;
            $attributeObject->addData($data);
            $attributeObject->save();
        }
    }

    /**
     * Retrieve customer attribute object
     *
     * @return \Magento\Customer\Model\Attribute
     */
    protected function initAttribute()
    {
        /** @var $attribute \Magento\Customer\Model\Attribute */
        $attribute = $this->attrFactory->create();
        $website = $this->websiteFactory->create();
        $attribute->setWebsite($website);
        return $attribute;
    }
}
