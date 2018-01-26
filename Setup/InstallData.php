<?php
/**
 * Created by PhpStorm.
 * User: kavindu
 */
namespace RedboxDigital\Linkedin\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    /**
     * Customer Setup
     *
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * InstallData constructor.
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Install
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, "linkedin_profile");

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, "linkedin_profile", array(
            "type"     => "varchar",
            "label"    => "Linkedin Profile",
            "input"    => "text",
            "visible"  => true,
            "required" => true,
            "validate_rules" => '{"max_text_length":255,"min_text_length":1}',
            "unique"     => true
        ));

        $linkedinProfile = $customerSetup->getEavConfig()
            ->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'linkedin_profile');
        $usedForms[]="adminhtml_customer";
        $usedForms[]="checkout_register";
        $usedForms[]="customer_account_create";
        $usedForms[]="customer_account_edit";
        $usedForms[]="adminhtml_checkout";
        $linkedinProfile->setData("used_in_forms", $usedForms)
            ->setData("is_used_for_customer_segment", true)
            ->setData("is_system", 0)
            ->setData("is_user_defined", 1)
            ->setData("is_visible", 1)
            ->setData("sort_order", 120);

        $linkedinProfile->save();

        $installer->endSetup();

    }
}