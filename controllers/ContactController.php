<?php

namespace app\controllers;

use app\components\SharedDataFilter;
use app\components\PaginationHelper;
use app\models\Contact;
use app\models\Organization;
use tebe\inertia\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\Response;

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
     * @throws HttpException
     */
    public function actionEdit($id)
    {
        $contact = Contact::findById($id);
        if (is_null($contact)) {
            throw new HttpException(404);
        }
        return $this->inertia('Contacts/Edit', [
            'contact' => $contact,
            'organizations' => Organization::getPairs()
        ]);
    }

    /**
     * @return Response
     */
    public function actionInsert()
    {
        $params = Yii::$app->request->post();
        $contact = Contact::fromArray($params);
        if ($contact->save()) {
            Yii::$app->session->setFlash('success', 'Contact created.');
            return $this->redirect(['contact/index']);
        }
        Yii::$app->session->setFlash('errors', $contact->getErrors());
        return $this->redirect(['contact/create']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws HttpException
     */
    public function actionUpdate($id)
    {
        $contact = Contact::findOne($id);
        if (is_null($contact)) {
            throw new HttpException(404);
        }
        $contact->attributes = Yii::$app->request->post();
        if ($contact->save()) {
            Yii::$app->session->setFlash('success', 'Contact updated.');
            return $this->redirect(['contact/edit', 'id' => $id]);
        }
        Yii::$app->session->setFlash('errors', $contact->getErrors());
        return $this->redirect(['contact/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        $contact = Contact::findOne($id);
        if (is_null($contact)) {
            throw new HttpException(404);
        }
        if ($contact->delete() > 0) {
            Yii::$app->session->setFlash('success', 'Contact deleted.');
        }
        return $this->redirect(['contact/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws HttpException
     */
    public function actionRestore($id)
    {
        $contact = Contact::findOne($id);
        if (is_null($contact)) {
            throw new HttpException(404);
        }
        if ($contact->restore() > 0) {
            Yii::$app->session->setFlash('success', 'Contact restored.');
        }
        return $this->redirect(['contact/edit', 'id' => $id]);
    }

}
