<?php

namespace app\controllers;

use app\filters\SharedDataFilter;
use app\helpers\PaginationHelper;
use app\models\Contact;
use app\models\Organization;
use tebe\inertia\web\Controller;
use Yii;
use yii\filters\AccessControl;

class ContactController extends Controller
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

        $dataProvider = Contact::findByParams($search, $trashed);

        return $this->inertia('Contacts/Index', [
            'filters' => [
                'search' => $search,
                'trashable' => $trashed,
            ],
            'contacts' => [
                'data' => array_map(function ($row) {
                    $row['name'] = $row['first_name'] . ' ' . $row['last_name'];
                    $row['organization'] = [
                        'name' => $row['organization_name']
                    ];
                    unset($row['first_name'], $row['last_name'], $row['organization_name']);
                    return $row;
                }, $dataProvider->getModels()
                ),
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
        return $this->inertia('Contacts/Create', [
            'organizations' => Organization::getPairs()
        ]);
    }

    /**
     * @param int $id
     * @return array|string
     */
    public function actionEdit($id)
    {
        $contact = Contact::findById($id);
        return $this->inertia('Contacts/Edit', [
            'contact' => $contact,
            'organizations' => Organization::getPairs()
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionInsert()
    {
        $params = Yii::$app->request->post();
        $organization = Contact::fromArray($params);
        if ($organization->save()) {
            Yii::$app->session->setFlash('success', 'Contact created.');
            return $this->redirect(['contact/index']);
        }
        Yii::$app->session->setFlash('errors', $organization->getErrors());
        return $this->redirect(['contact/create']);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionUpdate($id)
    {
        $organization = Contact::findOne($id);
        $organization->attributes = Yii::$app->request->post();
        if ($organization->save()) {
            Yii::$app->session->setFlash('success', 'Contact updated.');
            return $this->redirect(['contact/edit', 'id' => $id]);
        }
        Yii::$app->session->setFlash('errors', $organization->getErrors());
        return $this->redirect(['contact/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        if (Contact::deleteById($id) > 0) {
            Yii::$app->session->setFlash('success', 'Contact deleted.');
        }
        return $this->redirect(['contact/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionRestore($id)
    {
        if (Contact::restoreById($id) > 0) {
            Yii::$app->session->setFlash('success', 'Contact restored.');
        }
        return $this->redirect(['contact/edit', 'id' => $id]);
    }

}
