<?php

use richardfan\widget\JSRegister;
use yii\helpers\Url;

$this->registerCss("https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css");
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Item</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<?php
$this->registerJsFile("https://code.jquery.com/jquery-3.5.1.js");
$this->registerJsFile("https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js");
$this->registerJsFile("https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js");
?>
<?php JSRegister::begin(); 
$url = Url::toRoute(['manajemen-keuangan/pemasukan']);
?>
<script>
      
      $(function(){
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":{
                       "url": "<?= $url ?>",
                       "dataType": "json",
                       "type": "GET"
                     },
              "columns": [
                  { "data": "no" },
                  { "data": "nama" },
                  { "data": "no_hp" },
                  { "data": "aksi" },
              ]  
          });
        });
    
</script>
<?php JSRegister::end(); ?>