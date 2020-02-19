<?php

namespace app\commands;

use app\models\User;
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

        $seeder->table('accounts')->columns([
            'id' => 1,
            'name' => 'Acme Corporation',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ])->rowQuantity(1);

        $seeder->table('organizations')->columns([
            'id', // automatic pk
            'account_id' => $generator->relation('accounts', 'id'),
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

        $seeder->table('contacts')->columns([
            'id', // automatic pk
            'account_id' => $generator->relation('accounts', 'id'),
            'organization_id' => $generator->relation('organizations', 'id'),
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
            'account_id' => $generator->relation('accounts', 'id'),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => 'secret',
            'owner' => '0',
            'remember_token' => $faker->regexify('[A-Za-z0-9]{10}'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ])->rowQuantity(5);

        $seeder->refill();

        $user = new User();
        $user->detachBehaviors();
        $user->attributes = [
            'account_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => 1
        ];
        $user->save(false);

    }

}
