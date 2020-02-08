<?php

namespace app\commands;

use tebazil\dbseeder\Seeder;
use Yii;
use yii\console\Controller;

class DbController extends Controller
{

    public function actionSeed()
    {
        $db = Yii::$app->db;
        $db->open();

        $seeder = new Seeder($db->pdo);
        $generator = $seeder->getGeneratorConfigurator();
        $faker = $generator->getFakerConfigurator();

//        $db->createCommand()->truncateTable('accounts')->execute();
//        $db->createCommand()->executeResetSequence('accounts', 1);
//        $db->createCommand()->insert('accounts', [
//            'name' => 'Acme Corporation'
//        ])->execute();
//        $accountId = $db->getLastInsertID();

        $seeder->table('accounts')->columns([
            'name' => 'Acme Corporation'
        ])->rowQuantity(1);

        $accountId = $db->createCommand(
            'SELECT id FROM accounts'
        )->queryScalar();

        $seeder->table('organizations')->columns([
            'id', // automatic pk
            'account_id' => $accountId,
            'name' => $faker->company,
            'email' => $faker->companyEmail,
            'phone' => $faker->tollFreePhoneNumber,
            'address' => $faker->streetAddress,
            'city' => $faker->city,
            'region' => $faker->state,
            'country' => 'US',
            'postal_code' => $faker->postcode,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ])->rowQuantity(100);

        $organizationIds = $db->createCommand(
            'SELECT id FROM organizations'
        )->queryColumn();

        $seeder->table('contacts')->columns([
            'id', // automatic pk
            'account_id' => $accountId,
            'organization_id' => function () use ($organizationIds) {
                shuffle($organizationIds);
                return $organizationIds[0];
            },
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->tollFreePhoneNumber,
            'address' => $faker->streetAddress,
            'city' => $faker->city,
            'region' => $faker->state,
            'country' => 'US',
            'postal_code' => $faker->postcode,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ])->rowQuantity(100);

        $seeder->table('users')->columns([
            'id', // automatic pk
            'account_id' => $accountId,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => 'secret',
            'owner' => 0,
            'remember_token' => $faker->regexify('[A-Za-z0-9]{10}'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ])->rowQuantity(5);

        $seeder->refill();
    }

}
