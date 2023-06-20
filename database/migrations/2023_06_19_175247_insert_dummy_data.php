<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $customer_data = [
            [
                'customer_code' => 'DM001',
                'customer_name' => 'Dummy Customer One',
                'contact_person' => 'Dummy One',
                'phone' => '6470000001',
                'phone_2' => '',
                'phone_3' => '',
                'fax' => '',
                'address' => 'Toronto',
                'district_id' => 1,
                'delivery_order' => 0,
                'payment_method' => 'COD',
                'untrade' => 0,
                'remarks' => '',
            ],
            [
                'customer_code' => 'DM002',
                'customer_name' => 'Dummy Customer Two',
                'contact_person' => 'Dummy Two',
                'phone' => '6470000002',
                'phone_2' => '',
                'phone_3' => '',
                'fax' => '',
                'address' => 'Toronto',
                'district_id' => 2,
                'delivery_order' => 0,
                'payment_method' => 'COD',
                'untrade' => 0,
                'remarks' => '',
            ]
        ];
        DB::table('customers')->insert(
            $customer_data
        );

        $category_values_data = [
            [
                'value_code' => 'CN',
                'value_name' => 'Can',
                'order_in_category' => 1,
                'category_id' => 3
            ],
            [
                'value_code' => 'BN',
                'value_name' => 'Bean',
                'order_in_category' => 2,
                'category_id' => 3
            ],
            [
                'value_code' => 'PD',
                'value_name' => 'Powder',
                'order_in_category' => 3,
                'category_id' => 3
            ],
            [
                'value_code' => 'COD',
                'value_name' => 'Cash on Delivery',
                'order_in_category' => 1,
                'category_id' => 4
            ],
            [
                'value_code' => 'WA',
                'value_name' => 'Warehouse A',
                'order_in_category' => 1,
                'category_id' => 1
            ],
            [
                'value_code' => 'WB',
                'value_name' => 'Warehouse B',
                'order_in_category' => 2,
                'category_id' => 1
            ],
            [
                'value_code' => 'GL',
                'value_name' => 'Glass',
                'order_in_category' => 1,
                'category_id' => 2
            ],
            [
                'value_code' => 'BEAN',
                'value_name' => 'Bean',
                'order_in_category' => 2,
                'category_id' => 2
            ],
        ];

        DB::table('category_values')->insert(
            $category_values_data
        );

        $district_data = [
            [
                'district_code' => 'TO', 
                'district_name' => 'Tornoto', 
                'mon' => 0, 
                'car_no_mon' => 0, 
                'order_in_car_mon' => 0, 
                'tue' => 1, 
                'car_no_tue' => 1, 
                'order_in_car_tue' => 1, 
                'wed' => 0, 
                'car_no_wed' => 0, 
                'order_in_car_wed' => 0, 
                'thu' => 0, 
                'car_no_thu' => 0, 
                'order_in_car_thu' => 0, 
                'fri' => 0, 
                'car_no_fri' => 0, 
                'order_in_car_fri' => 0, 
                'sat' => 0, 
                'car_no_sat' => 0, 
                'order_in_car_sat' => 0
            ],
            [
                'district_code' => 'NY', 
                'district_name' => 'North York', 
                'mon' => 0, 
                'car_no_mon' => 0, 
                'order_in_car_mon' => 0, 
                'tue' => 1, 
                'car_no_tue' => 1, 
                'order_in_car_tue' => 2, 
                'wed' => 0, 
                'car_no_wed' => 0, 
                'order_in_car_wed' => 0, 
                'thu' => 1, 
                'car_no_thu' => 1, 
                'order_in_car_thu' => 1, 
                'fri' => 0, 
                'car_no_fri' => 0, 
                'order_in_car_fri' => 0, 
                'sat' => 0, 
                'car_no_sat' => 0, 
                'order_in_car_sat' => 0
            ],
            [
                'district_code' => 'SC', 
                'district_name' => 'Scarborough', 
                'mon' => 0, 
                'car_no_mon' => 0, 
                'order_in_car_mon' => 0, 
                'tue' => 1, 
                'car_no_tue' => 1, 
                'order_in_car_tue' => 3, 
                'wed' => 0, 
                'car_no_wed' => 0, 
                'order_in_car_wed' => 0, 
                'thu' => 1, 
                'car_no_thu' => 1, 
                'order_in_car_thu' => 2, 
                'fri' => 0, 
                'car_no_fri' => 0, 
                'order_in_car_fri' => 0, 
                'sat' => 1, 
                'car_no_sat' => 1, 
                'order_in_car_sat' => 1
            ]
        ];

        DB::table('districts')->insert(
            $district_data
        );

        $product_data = [
            [
                'product_code' => 'CN001', 
                'product_name' => 'Spam', 
                'inner_packing' => 0, 
                'unit' => 'can', 
                'unit_price' => 3, 
                'unit_cost' => 1.5, 
                'packing' => '500g', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 1
            ],
            [
                'product_code' => 'CN002', 
                'product_name' => 'Tuna', 
                'inner_packing' => 0, 
                'unit' => 'can', 
                'unit_price' => 4, 
                'unit_cost' => 1.5, 
                'packing' => '200g', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 1
            ],
            [
                'product_code' => 'CN003', 
                'product_name' => 'Spam (Box)', 
                'inner_packing' => 24, 
                'unit' => 'can', 
                'unit_price' => 90, 
                'unit_cost' => 24, 
                'packing' => '500g', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 1
            ],
            [
                'product_code' => 'BN001', 
                'product_name' => 'Red Bean', 
                'inner_packing' => 0, 
                'unit' => 'pack', 
                'unit_price' => 5, 
                'unit_cost' => 2.5, 
                'packing' => '500g', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 2
            ],
            [
                'product_code' => 'BN002', 
                'product_name' => 'Black Bean', 
                'inner_packing' => 0, 
                'unit' => 'pack', 
                'unit_price' => 5, 
                'unit_cost' => 2.5, 
                'packing' => '500g', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 2
            ],
            [
                'product_code' => 'PD001', 
                'product_name' => 'Flour', 
                'inner_packing' => 0, 
                'unit' => 'kg', 
                'unit_price' => 1, 
                'unit_cost' => 0.5, 
                'packing' => '1kg', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 3
            ],
            [
                'product_code' => 'PD002', 
                'product_name' => 'Cocoa', 
                'inner_packing' => 0, 
                'unit' => 'kg', 
                'unit_price' => 2, 
                'unit_cost' => 1, 
                'packing' => '1kg', 
                'count_inventory' => 10, 
                'remarks' => '', 
                'category_value_id' => 3
            ]
        ];

        DB::table('products')->insert(
            $product_data
        );

        $product_category_data = [
            [
                'product_code' => 'CN001',
                'category_code' => 'CAT', 
                'category_value_code' => 'CN', 
                'product_id' => 1, 
                'category_id' => 3, 
                'category_value_id' => 1
            ],
            [
                'product_code' => 'CN002',
                'category_code' => 'CAT', 
                'category_value_code' => 'CN', 
                'product_id' => 2, 
                'category_id' => 3, 
                'category_value_id' => 1
            ],
            [
                'product_code' => 'CN003',
                'category_code' => 'CAT', 
                'category_value_code' => 'CN', 
                'product_id' => 3, 
                'category_id' => 3, 
                'category_value_id' => 1
            ],
            [
                'product_code' => 'BN001',
                'category_code' => 'CAT', 
                'category_value_code' => 'BN', 
                'product_id' => 4, 
                'category_id' => 3, 
                'category_value_id' => 2
            ],
            [
                'product_code' => 'BN002',
                'category_code' => 'CAT', 
                'category_value_code' => 'BN', 
                'product_id' => 5, 
                'category_id' => 3, 
                'category_value_id' => 2
            ],
            [
                'product_code' => 'BN003',
                'category_code' => 'CAT', 
                'category_value_code' => 'BN', 
                'product_id' => 6, 
                'category_id' => 3, 
                'category_value_id' => 2
            ],
            [
                'product_code' => 'PD001',
                'category_code' => 'CAT', 
                'category_value_code' => 'PD', 
                'product_id' => 7, 
                'category_id' => 3, 
                'category_value_id' => 3
            ],
            [
                'product_code' => 'PD002',
                'category_code' => 'CAT', 
                'category_value_code' => 'PD', 
                'product_id' => 8, 
                'category_id' => 3, 
                'category_value_id' => 3
            ],
            [
                'product_code' => 'PD003',
                'category_code' => 'CAT', 
                'category_value_code' => 'PD', 
                'product_id' => 9, 
                'category_id' => 3, 
                'category_value_id' => 3
            ]
        ];

        DB::table('product_categories')->insert(
            $product_category_data
        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('customers')->truncate();
        DB::table('category_values')->truncate();
        DB::table('districts')->truncate();
        DB::table('products')->truncate();
        DB::table('product_categories')->truncate();
    }
};
