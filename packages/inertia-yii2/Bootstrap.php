<?php

namespace inertia;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\Request;
use yii\web\Response;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@inertia', __DIR__);
        #Yii::$app->controller->enableCsrfValidation = false;

        #Yii::$app->response->formatters[Response::FORMAT_JSON] = [
        #    'class' => 'yii\web\JsonResponseFormatter',
        #    'encodeOptions' => JSON_FORCE_OBJECT,
        #];

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, [$this, 'handleResponse']);
    }

    public function handleResponse($event)
    {
        /** @var Request $request */
        $request = Yii::$app->request;
        $method = $request->getMethod();

        /** @var Response $response */
        $response = $event->sender;

        if (!$request->headers->has('X-Inertia')) {
            return;
        }

        if ($response->isOk) {
            $response->format = Response::FORMAT_JSON;
            $response->headers->set('Vary', 'Accept');
            $response->headers->set('X-Inertia', 'true');
        }

        if ($method === 'GET') {
            if ($request->headers->has('X-Inertia-Version')) {
                $version = $request->headers->get('X-Inertia-Version', null, true);
                if ($version !== $this->getVersion()) {
                    if (Yii::$app->session->isActive) {
                        // Not needed in Yii2?
                        // $request->session()->reflash();
                    }
#                    $response->setStatusCode(409);
#                    $response->headers->set('X-Inertia-Location', $request->getAbsoluteUrl());
                }
            }
        }

        if ($response->getIsRedirection()) {
            if ($response->getStatusCode() === 302) {
                if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
                    $response->setStatusCode(303);
                }
            }
        }

        if ($response->headers->has('X-Redirect')) {
            $url = $response->headers->get('X-Redirect', null, true);
            $response->headers->set('Location', $url);
            $response->headers->set('X-Redirect', null);
        }

    }

    private function getVersion()
    {
        return 0;
    }

}
