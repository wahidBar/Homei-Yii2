<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekGaleri]].
 *
 * @see \app\models\ProyekGaleri
 */
class ProyekGaleriQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\ProyekGaleri[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek_galeri.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ProyekGaleri|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek_galeri.flag' => 1]);
        return parent::one($db);
    }
}
