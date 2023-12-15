<?php

namespace app\controllers;

/**
 * This is the class for controller "MasterKategoriKeuanganMasukController".
 * Modified by Defri Indra
 */
class MasterKategoriKeuanganMasukController extends \app\controllers\base\MasterKategoriKeuanganMasukController
{
    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }
}
