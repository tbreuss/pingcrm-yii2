<?php

namespace app\controllers;

use app\filters\SharedDataFilter;
use app\helpers\PaginationHelper;
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

    /**
     * @param string $search
     * @param string $trashed
     * @param string $remember
     * @param int $page
     * @return array|string
     */
    public function actionIndex($search = null, $trashed = null, $remember = null, $page = 1)
    {
        if ($remember === 'forget') {
            $search = null;
            $trashed = null;
        }

        $dataProvider = Organization::findByParams($search, $trashed);

        return $this->inertia('Organizations/Index', [
            'filters' => [
                'search' => $search,
                'trashable' => $trashed,
            ],
            'organizations' => [
                'data' => $dataProvider->getModels(),
                'links' => PaginationHelper::getLinks(
                    $dataProvider->getPagination(),
                    'index',
                    $search,
                    $trashed,
                    $page
                ),
            ]
        ]);
    }

    /**
     * @return array|string
     */
    public function actionCreate()
    {
        return $this->inertia('Organizations/Create');
    }

    /**
     * @param int $id
     * @return array|string
     */
    public function actionEdit($id)
    {
        $organization = Organization::findById($id);
        return $this->inertia('Organizations/Edit', [
            'organization' => $organization
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionInsert()
    {
        $params = Yii::$app->request->post();
        $organization = Organization::fromArray($params);
        if ($organization->save()) {
            Yii::$app->session->setFlash('success', 'Organization created.');
            return $this->redirect(['organization/index']);
        }
        Yii::$app->session->setFlash('errors', $organization->getErrors());
        return $this->redirect(['organization/create']);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionUpdate($id)
    {
        $organization = Organization::findOne($id);
        $organization->attributes = Yii::$app->request->post();
        if ($organization->save()) {
            Yii::$app->session->setFlash('success', 'Organization updated.');
            return $this->redirect(['organization/edit', 'id' => $id]);
        }
        Yii::$app->session->setFlash('errors', $organization->getErrors());
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        if (Organization::deleteById($id) > 0) {
            Yii::$app->session->setFlash('success', 'Organization deleted.');
        }
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionRestore($id)
    {
        if (Organization::restoreById($id) > 0) {
            Yii::$app->session->setFlash('success', 'Organization restored.');
        }
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

}
