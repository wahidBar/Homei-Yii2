<div class="card card-default">
    <div class="card-header">
        <strong>
            <?= $title ?? "Graph" ?>
        </strong>
        <?php if (isset($dropdown)) : ?>
            <div class="pull-right">
                <select class="form-control" name="" id="dropdowngraph-<?= $id ?>">
                    <option value="">-- Pilih --</option>
                    <?php foreach ($dropdown as $item) : ?>
                        <option value="<?= $item['id'] ?>"><?= $item['nama'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        <?php endif ?>
    </div>
    <div class="card-body">
        <div id="<?= $id ?>" style="width: calc(100% / 1 - 15px);"> </div>
    </div>
</div>


<?php

use richardfan\widget\JSRegister;

JSRegister::begin(); ?>
<script>
    var options<?= $id ?> = {
        chart: {
            height: 450,
            width: "100%",
            toolbar: {
                show: !1
            },
            type: "area",
        },
        stroke: {
            curve: "smooth",
        },
        grid: {
            borderColor: "#d5d5d5"
        },
        colors: <?= json_encode($graph['colors'])  ?>,
        dataLabels: {
            enabled: true,
            enabledOnSeries: undefined,
            formatter: function(val, opts) {
                return val
            },
            textAnchor: 'middle',
            distributed: false,
            offsetX: 0,
            offsetY: 0,
            style: {
                fontSize: '14px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 'bold',
                colors: undefined
            },
            background: {
                enabled: false,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.9,
                dropShadow: {
                    enabled: false,
                    top: 1,
                    left: 1,
                    blur: 1,
                    color: '#000',
                    opacity: 0.45
                }
            },
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        markers: {
            size: 0,
            hover: {
                size: 5
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#FDD835'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 0,
                stops: [0, 100, 100, 100]
            },
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center'
        },
        xaxis: {
            // type: 'datetime',
            labels: {
                style: {
                    colors: "#000"
                }
            },
            axisTicks: {
                show: !1,
            },
            categories: <?= json_encode($graph['categories'])  ?>,
            axisBorder: {
                show: !1
            },
            tickPlacement: "on"
        },
        yaxis: {
            tickAmount: 5,
            // max: 100,
            labels: {
                style: {
                    color: "#000"
                },
            },
            title: {
                text: '',
            },
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + " <?= $unit ?>"
                }
            }
        },
        series: <?= json_encode($graph['series']) ?>,
    };

    var chart<?= $id ?> = new ApexCharts(document.querySelector("#<?= $id ?>"), options<?= $id ?>);
    chart<?= $id ?>.render();
</script>
<?php JSRegister::end(); ?>