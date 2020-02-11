<?php

namespace app\controllers;

use app\models\Organization;
use inertia\Controller;
use Yii;
use yii\filters\AccessControl;

class OrganizationsController extends Controller
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
