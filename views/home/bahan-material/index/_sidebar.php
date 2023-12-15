<?php

use yii\helpers\Url;

$this->registerCssFile("@web/homepage/css/dropdown.css");
?>
<div class="siderbar">
    <div class="form-group">
        <input id="query_search" type="text" name="q" class="form-control" placeholder="Cari berdasarkan Nama..." value="<?= \yii\helpers\Html::encode(Yii::$app->request->get('q')) ?>">
    </div>
    <select name="material_filter" id="material_filter" class="form-control mb-1">
        <option value="">-- Pilih Material --</option>
        <?php foreach ($materials as $material) : ?>
            <option value="<?= $material->id ?>" <?= Yii::$app->request->get('material_filter') == $material->id ? "selected" : "" ?>><?= $material->nama ?></option>
        <?php endforeach; ?>
    </select>
    <select name="submaterial_filter" id="submaterial_filter" class="form-control mb-1" disabled>
        <option value="">-- Pilih Sub Material --</option>
    </select>
    <!-- <ul id="accordion" class="accordion">
        <li>
            <div class="link"><i class="fa fa-th-large"></i>Material<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
                <?php foreach ($materials as $material) : ?>
                    <li>
                        <a><?= $material->nama ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li>
            <div class="link"><i class="fa fa-th-large"></i>Sub Material<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu" id="filtersubmaterial">
            </ul>
        </li>
        <li>
            <div class="link"><i class="fa fa-square"></i>Merk<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
                <li><a href="#">Indo Cement</a></li>
                <li><a href="#">Semen Gresik</a></li>
                <li><a href="#">Semen Semesta</a></li>
            </ul>
        </li>
    </ul> -->
</div>

<?php
$url = Url::to(['/home/api/get-sub-material']);
$selected = Yii::$app->request->get('submaterial_filter');
$selected = $selected == "" ? 0 : intval($selected);
$js = <<<JS
function getAllUrlParams(url) {
    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

    // we'll store the parameters here
    var obj = {};

    // if query string exists
    if (queryString) {

    // stuff after # is not part of query string, so get rid of it
    queryString = queryString.split('#')[0];

    // split our query string into its component parts
    var arr = queryString.split('&');

    for (var i = 0; i < arr.length; i++) {
        // separate the keys and the values
        var a = arr[i].split('=');

        // set parameter name and value (use 'true' if empty)
        var paramName = a[0];
        var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

        // (optional) keep case consistent
        paramName = paramName.toLowerCase();
        if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

        // if the paramName ends with square brackets, e.g. colors[] or colors[2]
        if (paramName.match(/\[(\d+)?\]$/)) {

        // create key if it doesn't exist
        var key = paramName.replace(/\[(\d+)?\]/, '');
        if (!obj[key]) obj[key] = [];

        // if it's an indexed array e.g. colors[2]
        if (paramName.match(/\[\d+\]$/)) {
            // get the index value and add the entry at the appropriate position
            var index = /\[(\d+)\]/.exec(paramName)[1];
            obj[key][index] = paramValue;
        } else {
            // otherwise add the value to the end of the array
            obj[key].push(paramValue);
        }
        } else {
        // we're dealing with a string
        if (!obj[paramName]) {
            // if it doesn't exist, create property
            obj[paramName] = paramValue;
        } else if (obj[paramName] && typeof obj[paramName] === 'string'){
            // if property does exist and it's a string, convert it to an array
            obj[paramName] = [obj[paramName]];
            obj[paramName].push(paramValue);
        } else {
            // otherwise add the property
            obj[paramName].push(paramValue);
        }
        }
    }
    }

    return obj;
}

$(window).ready(() => {
    let material = $('#material_filter').val()
    if(material) {
        var data = new FormData();
        data.append( "id", material );
        data.append( "selected", $selected );
        fetch("$url", {
            method: "POST",
            body: data,
        }).then(res => res.json()).then(res => {
            $('#submaterial_filter').empty();
            $('#submaterial_filter').append("<option value=''>-- Pilih Sub Material --</option>");
            res.output.forEach(item => {
                let is_selected = item.id == res.selected ? "selected" : "";
                $('#submaterial_filter').append("<option value='" + item.id + "' " + is_selected + ">" + item.name + "</option>");
            });
            $('#submaterial_filter').removeAttr('disabled');
        });
    }
})

$('#query_search').on("keydown", (event) => {
    if (event.keyCode === 13) {
        let params = getAllUrlParams();
        params.q = event.target.value;
        let href = window.location.href.split('?')[0] + "?";
        let arr = Object.entries(params);
        let length = arr.length;
        for (let i =0; i < length;i++) {
            let key = arr[i][0];
            let val = arr[i][1];
            if(key != "") href += key+ "=" + val + "&";
        }
        window.location.href = href.slice(0,-1);
    }
});

$('#material_filter').on('change',(event) => {
    // var data = new FormData();
    // if(event.target.value) {
    //     data.append( "id", event.target.value );
    //     data.append( "selected", $selected );
    //     fetch("$url", {
    //         method: "POST",
    //         body: data,
    //     }).then(res => res.json()).then(res => {
    //         $('#submaterial_filter').empty();
    //         $('#submaterial_filter').append("<option value=''>-- Pilih Sub Material --</option>");
    //         res.output.forEach(item => {
    //             let is_selected = item.id == res.selected ? "selected" : "";
    //             $('#submaterial_filter').append("<option value='" + item.id + "' " + is_selected + ">" + item.name + "</option>");
    //         });
    //         $('#submaterial_filter').removeAttr('disabled');
    //     });
    // } else {
        let params = getAllUrlParams();
        params.material_filter = event.target.value;
        delete params.submaterial_filter
        let href = window.location.href.split('?')[0] + "?";
        let arr = Object.entries(params);
        let length = arr.length;
        for (let i =0; i < length;i++) {
            let key = arr[i][0];
            let val = arr[i][1];
            if(key != "") href += key+ "=" + val + "&";
        }
        window.location.href = href.slice(0,-1);
    // }
});

$('#submaterial_filter').on('change',(event) => {
    let params = getAllUrlParams();
    let href = window.location.href.split('?')[0] + "?";
    params.material_filter = $('#material_filter').val();
    params.submaterial_filter = event.target.value;
    let arr = Object.entries(params);
    let length = arr.length;
    for (let i =0; i < length;i++) {
        let key = arr[i][0];
        let val = arr[i][1];
        if(key != "") href += key+ "=" + val + "&";
    }
    window.location.href = href.slice(0,-1);
});

$('#submaterial_filter').on('change',(event) => {
    let params = getAllUrlParams();
    let href = window.location.href.split('?')[0] + "?";
    params.material_filter = $('#material_filter').val();
    params.submaterial_filter = event.target.value;
    let arr = Object.entries(params);
    let length = arr.length;
    for (let i =0; i < length;i++) {
        let key = arr[i][0];
        let val = arr[i][1];
        if(key != "") href += key+ "=" + val + "&";
    }
    window.location.href = href.slice(0,-1);
});

$('#sort').on('change',(event) => {
    let params = getAllUrlParams();
    let href = window.location.href.split('?')[0] + "?";
    params.sort = event.target.value;
    let arr = Object.entries(params);
    let length = arr.length;
    for (let i =0; i < length;i++) {
        let key = arr[i][0];
        let val = arr[i][1];
        if(key != "") href += key+ "=" + val + "&";
    }
    window.location.href = href.slice(0,-1);
});
JS;


$this->registerJs($js);
