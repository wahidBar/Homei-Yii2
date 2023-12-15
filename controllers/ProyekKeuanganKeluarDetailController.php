<?php

namespace app\controllers;

/**
 * This is the class for controller "ProyekKeuanganKeluarDetailController".
 * Modified by Defri Indra
 */
class ProyekKeuanganKeluarDetailController extends \app\controllers\base\ProyekKeuanganKeluarDetailController
{

    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }
}
