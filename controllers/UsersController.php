<?php

namespace app\controllers;

use inertia\Controller;
use yii\filters\AccessControl;

class UsersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $params = [
            'filters' => [
                'search' => null,
                'role' => null,
                'trashable' => null
            ],
            'users' => []
        ];
        return $this->inertia('Users/Index', $params);
    }

    public function actionEdit($id)
    {
        $params = [
            'user' => [
                'id' => $id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'sdfds',
                'owner' => true,
                'photo' => null,
                'deleted_at' => null
            ]
        ];
        return $this->inertia('Users/Edit', $params);
    }
}
