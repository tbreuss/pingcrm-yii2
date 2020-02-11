<?php

namespace inertia;

use Yii;

class Controller extends \yii\web\Controller
{
    public function inertia($component, $params = [])
    {
        $params = [
            'component' => $component,
            'props' => $this->getProps($params),
            'url' => $this->getUrl(),
            'version' => $this->getVersion()
        ];

        if (Yii::$app->request->headers->has('X-Inertia')) {
            return $params;
        }
        return $this->render('@inertia/views/inertia', [
            'page' => $params
        ]);
    }

    private function getFlashMessages()
    {
        $flash = [
            'success' => null,
            'error' => null,
        ];
        if (Yii::$app->session->hasFlash('success')) {
            $flash['success'] = Yii::$app->session->getFlash('success');
        }
        if (Yii::$app->session->hasFlash('error')) {
            $flash['error'] = Yii::$app->session->getFlash('error');
        }
        return $flash;
    }

    private function getErrors()
    {
        $errors = [];
        if (Yii::$app->session->hasFlash('errors')) {
            $errors = (array)Yii::$app->session->getFlash('errors');
        }
        return (object) $errors;
    }

    private function getUrl()
    {
        $url = Yii::$app->request->getUrl();
        return $url;
    }

    private function getProps($params = [])
    {
        $props = array_merge(
            [
                'auth' => [
                    'user' => $this->getUser()
                ],
                'flash' => $this->getFlashMessages(),
                'errors' => $this->getErrors(),
                'filters' => [
                    'search' => null,
                    'trashed' => null
                ]
            ],
            $params
        );
        return $props;
    }

    private function getUser()
    {
        $user = Yii::$app->getUser();
        if ($user->isGuest) {
            #return null;
        }

        $return = [
            'id' => 1,
            'first_name' => "John",
            'last_name' => "Doe",
            'email' => "johndoe@example.com",
            'role' => null,
            'account' => [
                'id' => 1,
                'name' => "Acme Corporation"
            ],
        ];
        return $return;
    }

    private function getVersion()
    {
        return '7f3cb61fee99321d705f22f5e215f10d';
    }
}
