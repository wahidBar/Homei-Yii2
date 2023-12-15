<?php

namespace app\models;

use Yii;
use \app\models\base\ApprovalSebelumPekerjaan as BaseApprovalSebelumPekerjaan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_approval_sebelum_pekerjaan".
 * Modified by Defri Indra M
 */
class ApprovalSebelumPekerjaan extends BaseApprovalSebelumPekerjaan
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public static function showAtIndex($id_project)
    {
        $query = ApprovalSebelumPekerjaan::find()
            ->where(['id_proyek' => $id_project])
            ->andWhere(['flag' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->select(['id', 'nama_progress', 'foto_material', 'keterangan', 'status', 'revisi', 'created_at', 'updated_at']);

        return $query;
    }

    // get list status
    public static function getListStatus()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'Pending'),
            self::STATUS_APPROVED => Yii::t('app', 'Approved'),
            self::STATUS_REJECTED => Yii::t('app', 'Rejected'),
        ];
    }

    // get status
    public function getStatus()
    {
        $list = self::getListStatus();
        return isset($list[$this->status]) ? $list[$this->status] : '';
    }

    // function generate label progress
    public function generateNamaProgress()
    {
        $kemajuan = ProyekKemajuan::find()->where([
            '{{%t_proyek_kemajuan}}.[[id]]' => $this->id_progress,
            '{{%t_proyek_kemajuan}}.[[flag]]' => 1,
        ])->one();
        if ($kemajuan == null) return null;

        $template = [$kemajuan->item];
        $grandmaster_parent = $kemajuan->listGrandMasterParent;
        if ($grandmaster_parent == null) {
            return $kemajuan->item;
        }

        foreach ($grandmaster_parent as $grandmaster) {
            $item = ProyekKemajuan::find()->where(['id' => $grandmaster])->one();
            $template[] = $item->item;
        }
        $template = implode(" -> ", array_reverse($template));

        return $template;
    }

    public function getUploadedPath()
    {
        return "/proyek/{$this->id_proyek}/approval_sebelum_pekerjaan";
    }
}
