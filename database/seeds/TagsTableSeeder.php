<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = array(
            [
                'name' => 'Sales Date',
                'value' => 'sales',
                'selector' => 'sales_date'
            ],
            [
                'name' => 'Ship Date',
                'value' => 'ship',
                'selector' => 'ship_date'
            ],
            [
                'name' => 'Account Connected Date',
                'value' => 'account',
                'selector' => 'account_connected_date'
            ],
            [
                'name' => 'Swab Returned Date',
                'value' => 'swab',
                'selector' => 'swab_returned_date'
            ],
            [
                'name' => 'Ship To Lab Date',
                'value' => 'shiptolab',
                'selector' => 'ship_to_lab_date'
            ],
            [
                'name' => 'Lab Received Date',
                'value' => 'labreceive',
                'selector' => 'lab_received_date'
            ],
            [
                'name' => 'Sequenced Date',
                'value' => 'sequenced',
                'selector' => 'sequenced_date'
            ],
            [
                'name' => 'Uploaded To Server Date',
                'value' => 'uploaded',
                'selector' => 'uploaded_to_server_date'
            ],
            [
                'name' => 'Bone Marrow Consent Date',
                'value' => 'boneconsent',
                'selector' => 'bone_marrow_consent_date'
            ],
            [
                'name' => 'Bone Marrow Shared Date',
                'value' => 'bone shared',
                'selector' => 'bone_marrow_shared_date'
            ],
        );

        DB::table('tags')->insert($tags);
    }
}
