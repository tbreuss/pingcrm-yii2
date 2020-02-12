<?php

namespace app\controllers;

use app\filters\SharedDataFilter;
use app\models\Organization;
use inertia\web\Controller;
use yii\filters\AccessControl;

class OrganizationController extends Controller
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
        $organizations = Organization::find()
            ->select('id, name, phone, city, deleted_at')
            ->asArray()
            ->all();
        $params = [
            'filters' => [
                'search' => null,
                'trashable' => null
            ],
            'organizations' => [
                'data' => $organizations,
                'links' => []
            ]
        ];
        return $this->inertia('Organizations/Index', $params);
    }
}
