<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;


class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('emizentech_shopbybrand_items'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'option_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'option_id'
            )
            ->addColumn(
                'admin_lable',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'admin_lable'
            )
            ->addColumn(
                'front_lable',
                Table::TYPE_TEXT,
                null,
                ['default' => null],
                'front_lable'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'attribute_id'
            )
            ->addColumn(
                'sort_order',
                Table::TYPE_INTEGER,
                null,
                ['default' => 0],
                'Sort Order'
            )
            ->addColumn(
                'src',
                Table::TYPE_TEXT,
                null,
                ['default' => null],
                'src'
            )
            ->addIndex(
                $installer->getIdxName(
                    'attribute_id',
                    ['option_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
			->setComment(
				'Brand Table'
			)
            ;
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
