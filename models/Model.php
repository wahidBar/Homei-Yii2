<?php

namespace app\models;

use Yii;
use yii\base\Model as BaseModel;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class Model extends BaseModel
{

    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model = new $modelClass;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

    public static function pagination($query, $options = [], $linkpager = "\\app\\components\\frontend\\LinkPager")
    {
        $countQuery = clone $query;
        $count = $countQuery->count();
        $pages = new Pagination(array_merge($options, ['totalCount' => $count]));
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return (object) [
            "model" => $models,
            "count" =>  $count,
            "_pagination" =>  $pages,
            "pagination" => $linkpager::widget(['pagination' => $pages])
        ];
    }
}
