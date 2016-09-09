<?php


namespace dalmate\ProductDesign\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Description of InstallSchema
 *
 * @author duc
 */
class InstallSchema implements InstallSchemaInterface{
    
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        
        $table = $setup->getConnection()->newTable(
                $setup->getTable("product_design")
                )
                ->addColumn(
                        "product_id",//field's name
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,//field's datatype
                        null,//field's data length
                        ["identity" => true, "unsigned" => true, "nullable" => false, "primary" => true],
                        "Product  ID"//comment
                        )
                ->addColumn(
                        "product_name",
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        "Product name"
                        )
                ->addColumn(
                        "product_type",
                        \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        ["nullable" => false, "default" => 0],
                        "Product type"
                        )
                ->addColumn(
                        "product_ower",
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ["nullable" => false],
                        "Owner id"
                        )
                ->addColumn(
                        "is_active",
                        \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                        null,
                        ["nullable" => false, "default" => true],
                        "Product status"
                        )
                ->addColumn(
                        "product_cost",
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ["nullable" => false, "default" => "0"],
                        "Product cost"
                        )
                ->addColumn(
                        "created_date",
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        ["nullable" => false],
                        "Product's created date"
                        )
                ->addColumn(
                        "updated_date",
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        ["nullable" => false],
                        "Product's update date"
                        );
        $setup->getConnection()->createTable($table);
        $table1 = $setup->getConnection()->newTable(
                $setup->getTable("product_info")
                )
                ->addColumn(
                        "product_info_id",//field's name
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,//field's datatype
                        null,//field's data length
                        ["identity" => true, "unsigned" => true, "nullable" => false, "primary" => true],
                        "Product info  ID"//comment
                        )
                ->addColumn(
                        "product_id",
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,//field's data length
                        ["unsigned" => true, "nullable" => false],
                        "Product info  ID"//comment
                        )
                ->addColumn(
                        "product_desc",
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        "Product's description"
                        );
        $setup->endSetup();
    }
}
