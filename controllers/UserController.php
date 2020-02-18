<?php

namespace app\controllers;

use app\components\SharedDataFilter;
use app\models\User;
use tebe\inertia\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\Response;

class UserController extends Controller
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
                        'roles' => ['@'],
                    ]
                ]
            ],
            [
                'class' => SharedDataFilter::class,
            ]
        ];
    }

    public function actionIndex($search = null, $role = null, $trashed = null, $remember = null)
    {
        if ($remember === 'forget') {
            $search = null;
            $role = null;
            $trashed = null;
        }

        $dataProvider = User::findByParams($search, $role, $trashed);

        return $this->inertia('Users/Index', [
            'filters' => [
                'search' => $search,
                'role' => $role,
                'trashable' => $trashed,
            ],
            'users' => $this->mapUsers($dataProvider->getModels()),
        ]);
    }

    /**
     * @param int $id
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($id)
    {
        $user = User::findById($id);
        if (is_null($user)) {
            throw new HttpException(404);
        }
        $user['owner'] = (bool)$user['owner'];
        return $this->inertia('Users/Edit', [
            'user' => $user
        ]);
    }

    /**
     * @return array|string
     */
    public function actionCreate()
    {
        return $this->inertia('Users/Create');
    }

    /**
     * @return Response
     */
    public function actionInsert()
    {
        $params = Yii::$app->request->post();
        $user = User::fromArray($params);
        if ($user->save()) {
            Yii::$app->session->setFlash('success', 'User created.');
            return $this->redirect(['user/index']);
        }
        Yii::$app->session->setFlash('errors', $user->getErrors());
        return $this->redirect(['user/create']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws HttpException
     */
    public function actionUpdate($id)
    {
        $user = User::findOne($id);
        if (is_null($user)) {
            throw new HttpException(404);
        }
        if ($user->email === 'johndoe@example.com') {
            Yii::$app->session->setFlash('error', 'Updating the demo user is not allowed.');
            return $this->redirect(['user/edit', 'id' => $id]);
        }
        $user->attributes = Yii::$app->request->post();
        if ($user->save()) {
            Yii::$app->session->setFlash('success', 'User updated.');
            return $this->redirect(['user/edit', 'id' => $id]);
        }
        Yii::$app->session->setFlash('errors', $user->getErrors());
        return $this->redirect(['user/edit', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        $user = User::findOne($id);
        if (is_null($user)) {
            throw new HttpException(404);
        }
        if ($user->email === 'johndoe@example.com') {
            Yii::$app->session->setFlash('error', 'Deleting the demo user is not allowed.');
            return $this->redirect(['user/edit', 'id' => $id]);
        }
        if ($user->delete() > 0) {
            Yii::$app->session->setFlash('success', 'User deleted.');
        }
        return $this->redirect(['user/edit', 'id' => $id]);
    }

    /**
     * @param $id
     * @return Response
     * @throws HttpException
     */
    public function actionRestore($id)
    {
        $user = User::findOne($id);
        if (is_null($user)) {
            throw new HttpException(404);
        }
        if ($user->restore() > 0) {
            Yii::$app->session->setFlash('success', 'User restored.');
        }
        return $this->redirect(['user/edit', 'id' => $id]);
    }

    /**
     * @param array $users
     * @return array
     */
    private function mapUsers(array $users)
    {
        return array_map(function ($row) {
            $row['name'] = $row['first_name'] . ' ' . $row['last_name'];
            $row['owner'] = (bool)$row['owner'];
            unset($row['first_name'], $row['last_name']);
            return $row;
        }, $users);
    }
}
