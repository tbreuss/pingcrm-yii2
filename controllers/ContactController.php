<?php

namespace app\controllers;

use app\filters\SharedDataFilter;
use inertia\web\Controller;
use yii\filters\AccessControl;

class ContactController extends Controller
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
                        'roles' => ['@']
                    ]
                ]
            ],
            [
                'class' => SharedDataFilter::class
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
