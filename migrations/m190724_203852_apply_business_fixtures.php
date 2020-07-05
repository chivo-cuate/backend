<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203852_apply_business_fixtures extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //Parámetros globales
        $this->insert('app_config', [
            'app_title' => 'Taquería "El Chivo Cuate"',
            'about' => 'Aplicación para mi taquería',
            'address' => 'Chilpancingo',
            'phone' => '+53 5 123 4567',
            'email_address' => 'fdbatista@gmail.com',
            'email_password' => 'Pass123*LTU',
            'email_host' => 'mail.google.com',
            'email_port' => '567',
            'email_encryption' => 'tls',
        ]);

        //Tipos de unidades de medida
        $this->insert('measure_unit_type', ['name' => 'Peso']);
        $this->insert('measure_unit_type', ['name' => 'Capacidad']);
        $this->insert('measure_unit_type', ['name' => 'Otros']);

        //Unidades de medida
        $this->insert('measure_unit', ['name' => 'Gramos', 'abbr' => 'gr', 'measure_unit_type_id' => 1]);
        $this->insert('measure_unit', ['name' => 'Kilogramos', 'abbr' => 'kg', 'measure_unit_type_id' => 1]);
        $this->insert('measure_unit', ['name' => 'Mililitros', 'abbr' => 'ml', 'measure_unit_type_id' => 2]);
        $this->insert('measure_unit', ['name' => 'Litros', 'abbr' => 'lt', 'measure_unit_type_id' => 2]);
        $this->insert('measure_unit', ['name' => 'Unidades', 'abbr' => 'u', 'measure_unit_type_id' => 3]);

        //Categorías de productos
        $this->insert('asset_category', ['name' => 'Alimentos', 'measure_unit_type_id' => 1]);
        $this->insert('asset_category', ['name' => 'Bebidas alcohólicas', 'measure_unit_type_id' => 2]);
        $this->insert('asset_category', ['name' => 'Bebidas no alcohólicas', 'measure_unit_type_id' => 2]);

        //Estados de las órdenes
        $this->insert('order_status', ['id' => 0, 'name' => 'Pendiente', 'slug' => 'PND']);
        $this->insert('order_status', ['id' => 1, 'name' => 'En elaboración', 'slug' => 'ELB']);
        $this->insert('order_status', ['id' => 2, 'name' => 'Lista', 'slug' => 'LST']);
        $this->insert('order_status', ['id' => 3, 'name' => 'Cerrada', 'slug' => 'CERR']);

        //Tipos de recursos
        $this->insert('asset_type', ['name' => 'Ingrediente']);
        $this->insert('asset_type', ['name' => 'Producto']);

        //Ingredientes
        $this->insert('asset', ['name' => 'Harina', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Cebolla', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Sal', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Pimienta', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Canela', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Picadillo', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Papas', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Aceite vegetal', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 4]);
        $this->insert('asset', ['name' => 'Tomate', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Queso', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 2]);
        $this->insert('asset', ['name' => 'Salsa tabasco', 'status' => 1, 'asset_type_id' => 1, 'measure_unit_id' => 4]);

        //Productos
        $this->insert('asset', ['name' => 'Cerveza Polar', 'status' => 1, 'asset_type_id' => 2, 'category_id' => 2, 'measure_unit_id' => 3]);
        $this->insert('asset', ['name' => 'Cola Cola', 'status' => 1, 'asset_type_id' => 2, 'category_id' => 3, 'measure_unit_id' => 3]);
        $this->insert('asset', ['name' => 'Malta', 'status' => 1, 'asset_type_id' => 2, 'category_id' => 3, 'measure_unit_id' => 3]);
        $this->insert('asset', ['name' => 'Red Bull', 'status' => 1, 'asset_type_id' => 2, 'category_id' => 3, 'measure_unit_id' => 3]);
        $this->insert('asset', ['name' => 'Papa frita', 'status' => 1, 'asset_type_id' => 2, 'category_id' => 1, 'measure_unit_id' => 1]);
        $this->insert('asset', ['name' => 'Taco', 'status' => 1, 'asset_type_id' => 2, 'category_id' => 1, 'measure_unit_id' => 1]);

        //Ingredientes de la papa frita
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 2, 'quantity' => 50, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 3, 'quantity' => 20, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 7, 'quantity' => 460, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 8, 'quantity' => 250, 'measure_unit_id' => 3]);
        
        //Ingredientes del taco
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 1, 'quantity' => 460, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 2, 'quantity' => 50, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 3, 'quantity' => 25, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 6, 'quantity' => 200, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 8, 'quantity' => 100, 'measure_unit_id' => 3]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 9, 'quantity' => 150, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 10, 'quantity' => 200, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 11, 'quantity' => 60, 'measure_unit_id' => 3]);
        
        //Existencias en almacén
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 1, 'quantity' => 500, 'price_in' => 12, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 2, 'quantity' => 200, 'price_in' => 3, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 3, 'quantity' => 200, 'price_in' => 10, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 4, 'quantity' => 100, 'price_in' => 7, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 5, 'quantity' => 50, 'price_in' => 5, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 6, 'quantity' => 250, 'price_in' => 15, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 7, 'quantity' => 100, 'price_in' => 20, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 8, 'quantity' => 500, 'price_in' => 30, 'measure_unit_id' => 4]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 9, 'quantity' => 250, 'price_in' => 2, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 10, 'quantity' => 150, 'price_in' => 40, 'measure_unit_id' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 11, 'quantity' => 70, 'price_in' => 8, 'measure_unit_id' => 4]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 12, 'quantity' => 480, 'price_in' => 25, 'measure_unit_id' => 5]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 13, 'quantity' => 720, 'price_in' => 20, 'measure_unit_id' => 5]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 14, 'quantity' => 240, 'price_in' => 15, 'measure_unit_id' => 5]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 15, 'quantity' => 600, 'price_in' => 30, 'measure_unit_id' => 5]);
        
        //Menu predeterminado
        $this->insert('menu', ['date' => date('Y-m-d'), 'branch_id' => 1]);
        
        //Productos del menu
        $this->insert('menu_asset', ['menu_id' => 1, 'asset_id' => 12, 'price' => 35, 'grams' => 350]);
        $this->insert('menu_asset', ['menu_id' => 1, 'asset_id' => 14, 'price' => 25, 'grams' => 250]);
        $this->insert('menu_asset', ['menu_id' => 1, 'asset_id' => 16, 'price' => 30, 'grams' => 460]);
        $this->insert('menu_asset', ['menu_id' => 1, 'asset_id' => 17, 'price' => 60, 'grams' => 460]);
        
        //Elaboradores
        $this->insert('menu_cook', ['menu_id' => 1, 'cook_id' => 7]);
        $this->insert('menu_cook', ['menu_id' => 1, 'cook_id' => 9]);
        $this->insert('menu_cook', ['menu_id' => 1, 'cook_id' => 10]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

      return false;
      }
     */
}
