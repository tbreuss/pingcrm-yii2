<?php

namespace app\controllers;

use app\filters\SharedDataFilter;
use app\models\Organization;
use inertia\web\Controller;
use Yii;
use yii\filters\AccessControl;

class OrganizationController extends Controller
{
    public $enableCsrfValidation = false;

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

    public function actionCreate()
    {
        return $this->inertia('Organizations/Create');
    }

    public function actionEdit($id)
    {
        $organization = Organization::findById($id);
        return $this->inertia('Organizations/Edit', [
            'organization' => $organization
        ]);
    }

    public function actionInsert()
    {
        $params = Yii::$app->request->post();
        $organization = Organization::createFromArray($params);
        if ($organization->save()) {
            Yii::$app->session->setFlash('success', 'Organization created.');
            return $this->redirect(['organization/index']);
        }
        Yii::$app->session->setFlash('errors', $organization->getErrors());
        return $this->redirect(['organization/create']);
    }

    public function actionUpdate($id)
    {
        Yii::$app->session->setFlash('success', 'Organization updated.');
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

    public function actionDelete($id)
    {
        if (Organization::deleteById($id) > 0) {
            Yii::$app->session->setFlash('success', 'Organization deleted.');
        }
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

    public function actionRestore($id)
    {
        if (Organization::restoreById($id) > 0) {
            Yii::$app->session->setFlash('success', 'Organization restored.');
        }
        return $this->redirect(['organization/edit', 'id' => $id]);
    }
}
