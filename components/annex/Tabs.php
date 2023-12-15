<?php

namespace app\components\annex;

use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @inheritdoc
 */
class Tabs extends \dmstr\bootstrap\Tabs
{
    public $navType = 'nav-pills nav-justified';
    public $headerOptions = [
        'class' => 'nav-item waves-effect waves-light pb-1',
    ];
    public $linkOptions = [
        'class' => 'nav-link',
    ];
    /**
     * Register assetBundle
     */
    public static function registerAssets()
    {
        TabBootstrapAssets::register(\Yii::$app->controller->getView());
    }


    /**
     * Sets the first visible tab as active.
     *
     * This method activates the first tab that is visible and
     * not explicitly set to inactive (`'active' => false`).
     * @since 2.0.7
     */
    protected function activateFirstVisibleTab()
    {
        foreach ($this->items as $i => $item) {
            $active = ArrayHelper::getValue($item, 'active', null);
            $visible = ArrayHelper::getValue($item, 'visible', true);
            if ($visible && $active !== false) {
                $this->items[$i]['active'] = true;
                return;
            }
        }
    }

    /**
     * Remember active tab state for this URL
     */
    public static function rememberActiveState($id = [])
    {
        self::registerAssets();
        $id = array_merge(["relation-tabs"], $id);
        $js = "";
        foreach ($id as $i) {
            $js .= <<<JS
            jQuery("#$i > li > a").on("click", function () {
                setStorage(this);
            });

            jQuery(document).on('pjax:end', function() {
                setStorage($('#$i .active A'));
            });

            jQuery(window).on("load", function () {
                initialSelect();
            });
JS;
        }

        if (\Yii::$app->request->isAjax) {
            echo "<script type='text/javascript'>{$js}</script>";
        } else {
            // Register cookie script
            \Yii::$app->controller->getView()->registerJs(
                $js,
                View::POS_END,
                'rememberActiveState'
            );
        }
    }

    /**
     * Clear the localStorage of your browser
     */
    public static function clearLocalStorage()
    {
        // TODO @c.stebe - This removes all cookies, eg. the ones set from Yii 2 debug toolbar
        /*\Yii::$app->controller->getView()->registerJs(
            'window.localStorage.clear();',
            View::POS_READY,
            'clearLocalStorage'
        );*/
    }
}
