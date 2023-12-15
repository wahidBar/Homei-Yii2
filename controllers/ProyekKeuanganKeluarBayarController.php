<?php

namespace app\controllers;

/**
 * This is the class for controller "ProyekKeuanganKeluarBayarController".
 * Modified by Defri Indra
 */
class ProyekKeuanganKeluarBayarController extends \app\controllers\base\ProyekKeuanganKeluarBayarController
{
    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }
}
