<?php

use yii\db\Migration;

/**
 * Class m190808_195638_create_product_ingredient_view
 */
class m190724_182512_create_product_ingredient_view extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute("
            create or replace view product_ingredient
            as
            select
                asset.id as asset_id,
                asset.name as asset_name,
                asset_component.component_id,
                asset_component.quantity as required_quantity,
                coalesce(stock.quantity, 0) as stock_quantity,
                coalesce(stock.price_in, 0) as price_in,
                asset_component.measure_unit_id as measure_unit_id,
                floor((coalesce(stock.quantity, 0) / asset_component.quantity)) units_left
                from asset
                inner join asset_component on (asset.id = asset_component.asset_id)
                left join stock on (asset_component.component_id = stock.asset_id)
                order by name, units_left
            ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190808_195638_create_product_ingredient_view cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190808_195638_create_product_ingredient_view cannot be reverted.\n";

      return false;
      }
     */
}
