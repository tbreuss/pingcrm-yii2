<?php

namespace app\controllers;

use app\filters\SharedDataFilter;
use inertia\web\Controller;
use yii\filters\AccessControl;

class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            [
                'class' => SharedDataFilter::class,
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
