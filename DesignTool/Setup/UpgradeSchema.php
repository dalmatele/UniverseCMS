<?php

namespace dalmate\ProductDesign\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use dalmate\ProductDesign\Setup\InstallSchema;

class UpgradeSchema implements UpgradeSchemaInterface{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){
        $setup->startSetup();
        $install = new InstallSchema();
        $install->install($setup, $context);
//        if(!$context->getVersion()){
//            //it's a new install
//            $install = new InstallSchema();
//            $install->install($setup, $context);
//        }
//        if(version_compare($context->getVersion(), "0.0.2") < 0){
//            //it's a new install
//            $install = new InstallSchema();
//            $install->install($setup, $context);
//        }
//        if(version_compare($context->getVersion(), "0.0.3") < 0){
//            
//        }
        $setup->endSetup();
    }
}


