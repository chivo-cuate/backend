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
        
        //Tipos de recursos
        $this->insert('asset_type', ['name' => 'Ingrediente']);
        $this->insert('asset_type', ['name' => 'Producto']);
        
        //Categorías de productos
        $this->insert('asset_category', ['name' => 'Alimentos']);
        $this->insert('asset_category', ['name' => 'Bebidas alcohólicas']);
        $this->insert('asset_category', ['name' => 'Bebidas no alcohólicas']);
        
        //Estados de las órdenes
        $this->insert('order_status', ['id' => 0, 'name' => 'Pendiente']);
        $this->insert('order_status', ['id' => 1, 'name' => 'Asignada']);
        $this->insert('order_status', ['id' => 2, 'name' => 'Elaborada']);
        $this->insert('order_status', ['id' => 3, 'name' => 'Cerrada']);
        
        //Ingredientes
        $this->insert('asset', ['name' => 'Tortilla de harina', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Cebolla', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Sal', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Pimienta', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Canela', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Picadillo', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Papas', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Aceite vegetal', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Tomate', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Queso', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        $this->insert('asset', ['name' => 'Salsa tabasco', 'status' => 1, 'asset_type_id' => 1, 'branch_id' => 1]);
        
        //Productos
        $this->insert('asset', ['name' => 'Cerveza Polar', 'status' => 1, 'asset_type_id' => 2, 'branch_id' => 1, 'category_id' => 2]);
        $this->insert('asset', ['name' => 'Cola Cola 600', 'status' => 1, 'asset_type_id' => 2, 'branch_id' => 1, 'category_id' => 3]);
        $this->insert('asset', ['name' => 'Malta', 'status' => 1, 'asset_type_id' => 2, 'branch_id' => 1, 'category_id' => 3]);
        $this->insert('asset', ['name' => 'Red Bull 350', 'status' => 1, 'asset_type_id' => 2, 'branch_id' => 1, 'category_id' => 3]);
        $this->insert('asset', ['name' => 'Papa frita', 'status' => 1, 'asset_type_id' => 2, 'branch_id' => 1, 'category_id' => 1]);
        $this->insert('asset', ['name' => 'Taco', 'status' => 1, 'asset_type_id' => 2, 'branch_id' => 1, 'category_id' => 1]);
        
        //Unidades de medida
        $this->insert('measure_unit', ['name' => 'Kilogramos', 'abbr' => 'kg']);    //2
        $this->insert('measure_unit', ['name' => 'Litros', 'abbr' => 'lt']);        //4
        $this->insert('measure_unit', ['name' => 'Unidades', 'abbr' => 'u']);       //5
        
        //Ingredientes de la papa frita
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 2, 'quantity' => 0.5, 'measure_unit_id' => 3]);
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 3, 'quantity' => 0.05, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 7, 'quantity' => 0.46, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 16, 'component_id' => 8, 'quantity' => 0.2, 'measure_unit_id' => 2]);
        
        //Ingredientes del taco
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 1, 'quantity' => 4, 'measure_unit_id' => 3]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 2, 'quantity' => 1, 'measure_unit_id' => 3]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 3, 'quantity' => 0.02, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 6, 'quantity' => 0.2, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 8, 'quantity' => 0.1, 'measure_unit_id' => 2]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 9, 'quantity' => 1, 'measure_unit_id' => 3]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 10, 'quantity' => 0.1, 'measure_unit_id' => 1]);
        $this->insert('asset_component', ['asset_id' => 17, 'component_id' => 11, 'quantity' => 0.15, 'measure_unit_id' => 2]);
        
        //Existencias en almacén
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 1, 'quantity' => 500, 'measure_unit_id' => 3, 'price_in' => 12]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 2, 'quantity' => 200, 'measure_unit_id' => 3, 'price_in' => 3]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 3, 'quantity' => 200, 'measure_unit_id' => 1, 'price_in' => 10]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 4, 'quantity' => 100, 'measure_unit_id' => 1, 'price_in' => 7]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 5, 'quantity' => 50, 'measure_unit_id' => 1, 'price_in' => 5]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 6, 'quantity' => 250, 'measure_unit_id' => 1, 'price_in' => 15]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 7, 'quantity' => 100, 'measure_unit_id' => 1, 'price_in' => 20]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 8, 'quantity' => 500, 'measure_unit_id' => 2, 'price_in' => 30]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 9, 'quantity' => 250, 'measure_unit_id' => 3, 'price_in' => 2]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 10, 'quantity' => 150, 'measure_unit_id' => 1, 'price_in' => 40]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 11, 'quantity' => 70, 'measure_unit_id' => 2, 'price_in' => 8]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 12, 'quantity' => 480, 'measure_unit_id' => 3, 'price_in' => 25]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 13, 'quantity' => 720, 'measure_unit_id' => 3, 'price_in' => 20]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 14, 'quantity' => 240, 'measure_unit_id' => 3, 'price_in' => 15]);
        $this->insert('stock', ['branch_id' => 1, 'asset_id' => 15, 'quantity' => 600, 'measure_unit_id' => 3, 'price_in' => 30]);
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
