<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekDokumen]].
 *
 * @see \app\models\ProyekDokumen
 */
class ProyekDokumenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\ProyekDokumen[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek_dokumen.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ProyekDokumen|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek_dokumen.flag' => 1]);
        return parent::one($db);
    }
}
