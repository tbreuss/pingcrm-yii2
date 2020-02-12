<?php

namespace inertia\web;

use inertia\Bootstrap;
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

    private function getProps($params = [])
    {
        return array_merge(
            Bootstrap::getShared(),
            $params
        );
    }

    private function getUrl()
    {
        $url = Yii::$app->request->getUrl();
        return $url;
    }

    private function getVersion()
    {
        return '7f3cb61fee99321d705f22f5e215f10d';
    }
}
