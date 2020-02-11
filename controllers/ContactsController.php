<?php

namespace app\controllers;

use inertia\Controller;
use yii\filters\AccessControl;

class ContactsController extends Controller
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
                'trashable' => null
            ],
            'contacts' => [
                'data' => [],
                'links' => []
            ]
        ];
        return $this->inertia('Contacts/Index', $params);
    }
}
