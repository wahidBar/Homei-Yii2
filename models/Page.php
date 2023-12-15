<?php

namespace app\models;

use Yii;
use \app\models\base\Page as BasePage;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_pages".
 * Modified by Defri Indra M
 */
class Page extends BasePage
{
    // increment view count
    public function incrementViewCount()
    {
        $this->view_count++;
        $this->save();
    }
}
