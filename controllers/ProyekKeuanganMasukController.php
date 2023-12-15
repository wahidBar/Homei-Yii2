<?php

namespace app\controllers;

/**
 * This is the class for controller "ProyekKeuanganMasukController".
 * Modified by Defri Indra
 */
class ProyekKeuanganMasukController extends \app\controllers\base\ProyekKeuanganMasukController
{
    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }
}
