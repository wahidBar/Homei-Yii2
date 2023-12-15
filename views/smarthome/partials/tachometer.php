<?php

use richardfan\widget\JSRegister;

?>
<div id="tachometer<?= $id ?>"></div>

<?php JSRegister::begin() ?>
<script>
    var options = {
        series: [<?= round(($value / $max) * 100) ?>],
        chart: {
            type: 'radialBar',
            offsetY: -20,
            sparkline: {
                enabled: true
            }
        },
        colors: ['<?= $color ?>'],
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,
                track: {
                    background: "#e7e7e7",
                    strokeWidth: '97%',
                    margin: 5, // margin is in pixels
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 0,
                        color: '#999',
                        opacity: 1,
                        blur: 2
                    }
                },
            }
        },
        grid: {
            padding: {
                top: -10
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                shadeIntensity: 0.4,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 53, 91]
            },
        },
        labels: ["<?= round($value) ?> / <?= round($max) ?> <?= $unit ?>"],
    };

    var tachometer<?= $id ?> = new ApexCharts(document.querySelector("#tachometer<?= $id ?>"), options);
    tachometer<?= $id ?>.render();
</script>
<?php JSRegister::end() ?>