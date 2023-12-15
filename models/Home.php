<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_satuan".
 * Modified by Defri Indra M
 */
class Home
{
    public function getprov($searchTerm = "")
    {

        $query = new Query();
        $query->select(['id', 'nama'])
            ->from('wilayah_provinsi')
            ->where("nama like '%" . $searchTerm . "%' ")
            ->orderBy('id', 'asc');
        $command = $query->createCommand();
        $dataprov = $command->queryAll();

        $data = array();
        foreach ($dataprov as $prov) {
            $data[] = array("id" => $prov['id'], "text" => $prov['nama']);
        }
        return $data;
    }

    public function getkab($id_prov, $searchTerm = "")
    {

        // var_dump($id_prov);die;
        $query = new Query();
        $query->select(['id', 'nama'])
            ->from('wilayah_kota')
            ->where(['provinsi_id' => $id_prov])
            ->andFilterWhere(['like', 'nama',$searchTerm])
            ->orderBy('id', 'asc');
        $command = $query->createCommand();
        $datakab = $command->queryAll();

        $data = array();
        foreach ($datakab as $kab) {
            $data[] = array("id" => $kab['id'], "text" => $kab['nama']);
        }
        return $data;
    }

    public function getkec($id_kec, $searchTerm = "")
    {

        // var_dump($id_kec);die;
        $query = new Query();
        $query->select(['id', 'nama'])
            ->from('wilayah_kecamatan')
            ->where(['kota_id' => $id_kec])
            ->andFilterWhere(['like', 'nama',$searchTerm])
            ->orderBy('id', 'asc');
        $command = $query->createCommand();
        $datakec = $command->queryAll();

        $data = array();
        foreach ($datakec as $kec) {
            $data[] = array("id" => $kec['id'], "text" => $kec['nama']);
        }
        return $data;
    }

    public function getdes($id_desa, $searchTerm = "")
    {

        // var_dump($id_desa);die;
        $query = new Query();
        $query->select(['id', 'nama'])
            ->from('wilayah_desa')
            ->where(['kecamatan_id' => $id_desa])
            ->andFilterWhere(['like', 'nama',$searchTerm])
            ->orderBy('id', 'asc');
        $command = $query->createCommand();
        $datades = $command->queryAll();

        $data = array();
        foreach ($datades as $desa) {
            $data[] = array("id" => $desa['id'], "text" => $desa['nama']);
        }
        return $data;
    }
}
