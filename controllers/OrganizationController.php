<?php

namespace app\controllers;

use app\components\SharedDataFilter;
use app\components\PaginationHelper;
use app\models\Organization;
use tebe\inertia\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\Response;

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
     * @throws HttpException
     */
    public function actionEdit($id)
    {
        $organization = Organization::findById($id);
        if (is_null($organization)) {
            throw new HttpException(404);
        }
        return $this->inertia('Organizations/Edit', [
            'organization' => $organization
        ]);
    }

    /**
     * @return Response
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
     * @return Response
     * @throws HttpException
     */
    public function actionUpdate($id)
    {
        $organization = Organization::findOne($id);
        if (is_null($organization)) {
            throw new HttpException(404);
        }
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
     * @return Response
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        $organization = Organization::findOne($id);
        if (is_null($organization)) {
            throw new HttpException(404);
        }
        if ($organization->delete() > 0) {
            Yii::$app->session->setFlash('success', 'Organization deleted.');
        }
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws HttpException
     */
    public function actionRestore($id)
    {
        $organization = Organization::findOne($id);
        if (is_null($organization)) {
            throw new HttpException(404);
        }
        if ($organization->restore() > 0) {
            Yii::$app->session->setFlash('success', 'Organization restored.');
        }
        return $this->redirect(['organization/edit', 'id' => $id]);
    }

}
