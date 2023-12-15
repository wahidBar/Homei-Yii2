<?php
$list = \app\components\Constant::calculatorAllowedChar();
?>
<div id="modalcontent">
    <p>
        Cara penulisan rumus adalah dengan menambahkan spasi sebagai pemisah dari setiap karakternya.
    </p>
    <p>
        Berikut adalah list dari karakter yang Diijinkan untuk digunakan (NB: rumus ini akan menjadi acuan untuk pengisian dari barang) :
    </p>
    <table class="table table-striped">
        <tbody>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td>
                        <?= $item ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>