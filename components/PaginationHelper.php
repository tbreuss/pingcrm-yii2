<?php

namespace app\components;

use yii\data\Pagination;
use yii\helpers\Url;

class PaginationHelper
{
    /**
     * @param Pagination $pagination
     * @param string $route
     * @param string $search
     * @param string $trashed
     * @param int $page
     * @return array
     */
    public static function getLinks(Pagination $pagination, $route = 'index', $search = null, $trashed = null, $page = 1)
    {
        $pageCount = $pagination->getPageCount();

        $links = [];
        if ($pageCount > 1) {
            $params = [];
            if (isset($search)) {
                $params['search'] = $search;
            }
            if (isset($trashed)) {
                $params['trashed'] = $trashed;
            }
            $links[] = [
                'url' => $page > 1 ? Url::toRoute(array_merge($params, [$route, 'page' => $page - 1])) : null,
                'label' => 'Previous',
                'active' => false
            ];

            for ($i = 1; $i <= $pageCount; $i++) {
                $links[] = [
                    'url' => Url::toRoute(array_merge($params, [$route, 'page' => $i])),
                    'label' => $i,
                    'active' => $page == $i
                ];
            }

            $links[] = [
                'url' => $page < $pageCount ? Url::toRoute(array_merge($params, [$route, 'page' => $page + 1])) : null,
                'label' => 'Next',
                'active' => false
            ];
        }
        return $links;
    }
}
