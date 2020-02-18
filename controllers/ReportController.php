<?php

namespace app\controllers;

use app\components\SharedDataFilter;
use tebe\inertia\web\Controller;
use yii\filters\AccessControl;

class ReportController extends Controller
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
        return $this->inertia('Reports/Index', $params);
    }
}
