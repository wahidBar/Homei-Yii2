<?php

namespace app\controllers;

/**
 * This is the class for controller "MasterKategoriKeuanganKeluarController".
 * Modified by Defri Indra
 */
class MasterKategoriKeuanganKeluarController extends \app\controllers\base\MasterKategoriKeuanganKeluarController
{
    /**
     * RBAC filter
     */
    public function behaviors()
    {
        // dd(\app\models\Action::getAccess($this->id, true, "id_project"));
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }
}
