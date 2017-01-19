<?php
/**
 * Author: iCube
 *
 * - CMS Page for How to vendor PIM
 */
?>
<?php

$installer = $this;
$installer->startSetup();

$config = Mage::getModel('core/config');          
$config->saveConfig('design/package/name', 'rwd', 'default');
$config->saveConfig('design/theme/default', 'default', 'default');
    
    unset ($config);

/* Update image Tutorial */
$cmsPage = Mage::getModel('cms/page')->load('how-to-update-image', 'identifier');

$pageContent =<<<EOF
<ol start="1">
<li>&nbsp;Siapkan file image dengan ketentuan berikut ini :</li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_image1.png}}" /></p>
<ul>
<li>Tipe file JPG (*.JPG)</li>
<li>Range size : 150 - 250 kB</li>
<li>Ukuran file : 900 x 900 pixel</li>
<li>Resolusi : 72 dpi</li>
<li>Background : Putih</li>
<li>Penamaan file :</li>
<ul>
<li>[SKUvendor]_IMG_1_1 = anglel</li>
<li>[SKUvendor]_IMG_1_2 = angle2</li>
<li>[SKUvendor]_IMG_1_3 = angle3</li>
</ul>
</ul>
<ol start="2">
<li>
<pre>Buka My Computer lalu masukkan URL : <a href="ftp://vendor.klikmro.com/">ftp://vendor.klikmro.com</a></pre>
</li>
</ol><ol start="3"><ol start="3">
<li>
<pre>Masukkan username dan password Anda sesuai dengan email yang dikirim KlikMRO.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image2.png}}" /></p>
<p>&nbsp;</p>
<ol start="4"><ol start="4">
<li>
<pre>Masukkan image dengan menyalin semua file ke folder yang sudah tersedia.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image3.png}}" /></p>
<ol start="5"><ol start="5">
<li>
<pre>Login ke Vendor Dashboard <a href="http://vendor.klikmro.com/">http://vendor.klikmro.com</a> dengan memasukkan username dan password Anda.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image4.png}}" /></p>
<ol start="5"><ol start="5">
<li>
<pre>Pilih menu Product Catalog lalu klik Update Image.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image5.png}}" /></p>
<ol start="7"><ol start="7">
<li>
<pre>Download template CSV dengan menekan link &ldquo;Download the sample CSV&rdquo;.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image6.png}}" /></p>
<ol start="8"><ol start="8">
<li>
<pre>Isi template sesuai dengan vendor_sku dan nama image product.&nbsp;</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image7.png}}" /></p>
<ol start="9"><ol start="9">
<li>
<pre>Simpan dengan nama dan format *.csv</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image8.png}}" /></p>
<ol start="10"><ol start="10">
<li>
<pre>Browse file Anda, pastikan file sudah terpilih dengan benar. Lalu tekan tombol submit.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image9.png}}" /></p>
<ol start="11"><ol start="11">
<li>
<pre>Image Anda telah berhasil diperbarui.</pre>
</li>
</ol></ol>
<p><img alt="Update Image" src="{{skin url=images/update_image7.png}}" /></p>
<ol start="11">
<li>
<pre>Apabila Anda memiliki pertanyaan atau kesulitan, silahkan hubungi kami di <a href="mailto:vendor@klikMRO.com">vendor@klikMRO.com</a> atau 021-5829051.</pre>
</li>
</ol>
<p>&nbsp;</p>
EOF;


if($cmsPage->getId()){
    $cmsPage->setTitle('How to Update Image');
}

$cmsPage->setStores(0)
        ->setIdentifier('how-to-update-image')
        ->setContent($pageContent)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();


/* Update price Tutorial */
$cmsPage = Mage::getModel('cms/page')->load('how-to-update-price', 'identifier');

$pageContent =<<<EOF
<pre><b>Panduan Memperbarui Harga (Update Price):</b></pre>
<ol start="1">
    <li>
        <p>Pilih Update Price pada menu Product Catalog</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_price1.png}}" /></p>
<ol start="2">
    <li>
        <p>Download template &ldquo;price.csv&rdquo; yang telah tersedia dengan menekan link &ldquo;Download the Sample CSV&rdquo;.</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_price2.png}}" /></p>
<ol start="3">
    <li>
        <p>Masukkan kode produk yang ingin diperbarui ke kolom &ldquo;vendor_sku&rdquo;</p>
    </li>
</ol>
<ol start="4">
    <li>
    <p>Masukkan harga (exc. PPN) sesuai dengan perubahan yang diinginkan.</p>
    <pre>Keterangan :</pre>
    <ul>
    <li>
    <pre>msrp: Market Price / SRP (Sales Retail Price)</pre>
    </li>
    <li>
    <pre>suggested_pr&nbsp; ice : Harga Website</pre>
    </li>
    <li>
    <pre>special_price: Harga Coret</pre>
    </li>
    </ul>
    <pre class="DefaultStyle"><b><i>*CONTOH PENGISIAN TEMPLATE</i></b></pre>
    <pre class="DefaultStyle"><b><i>*HARGA SETELAH PPN (KALKULASI OTOMATIS DARI SISTEM)</i></b></pre>
    <pre class="DefaultStyle"><b>Tampilan produk bimetal1 :</b></pre>
    <pre class="DefaultStyle">bimetal1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rp110.000,00</pre>
    <pre class="DefaultStyle"><i>-&nbsp; Harga ini diambil dari suggested_price (Rp100.000,00) + PPN 10% = Rp110.000,00</i></pre>
    <pre class="DefaultStyle"><b>Tampilan produk bimetal2 :</b></pre>
    <pre class="DefaultStyle">bimetal2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strike>Rp132.000,00</strike></pre>
    <pre class="DefaultStyle"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>Rp110.000,00</pre>
    <pre class="DefaultStyle"><i>- Harga yang tercoret diambil dari suggested_price + PPN 10% = Rp132.000,00</i></pre>
    <pre class="DefaultStyle"><i>- Harga terakhir diambil dari special_price + PPN 10% = Rp110.000,00</i></pre>
    <pre class="DefaultStyle"><b><i>*JIKA ADA PERUBAHAN HARGA BELI KLIKMRO, MAKA DAPAT DIKIRIMKAN MANUAL DE</i></b><br /> <b><i>NGAN E - MAIL</i></b></pre>
    </li>
</ol>
<ol start="5">
    <li>
        <p>Simpan file dan mohon untuk tidak mengubah&nbsp;format file (*.csv).</p>
        </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_price3.png}}" /></p>
<ol start="6">
    <li>
    <p>Attach file *.csv Anda dengan menekan tombol Browse. Pastikan file yang dipilih adalah benar, lalu tekan Submit.</p>
    </li>
</ol>
<ol start="7">
    <li>
        <p>Produk Anda telah telah sukses diperbarui.</p>
    </li>
</ol>
<ol start="8">
    <li>
        <p>Apabila Anda memiliki pertanyaan atau kesulitan, silahkan hubungi kami di <a href="mailto:vendor@klikMRO.com">vendor@klikMRO.com</a> atau 021-5829051.<p>
    </li>
</ol>
EOF;


if($cmsPage->getId()){
    $cmsPage->setTitle('How to Update Price');
}

$cmsPage->setStores(0)
        ->setIdentifier('how-to-update-price')
        ->setContent($pageContent)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

/* Update stock Tutorial */
$cmsPage = Mage::getModel('cms/page')->load('how-to-update-stock', 'identifier');

$pageContent =<<<EOF
<ol start="1">
    <li>
        <p>Pilih Update Stock pada menu Product Catalog</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_stock1.png}}" /></p>
<ol start="2">
    <li>
        <p>Download template &ldquo;stock.csv&rdquo; dengan menekan link &ldquo;Download the Sample CSV&rdquo;</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_stock2.png}}" /></p>
<ol start="3">
    <li>
        <p>Masukkan kode produk yang ingin diperbarui ke kolom &ldquo;vendor_sku&rdquo;</p>
        </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_stock3.png}}" /></p>
<ol start="4">
    <li>
        <p>Masukkan nilai stok sesuai dengan ketersediaan</p>
        <p class="DefaultStyle"><b><i>*CONTOH PENGISIAN TEMPLATE</i></b></p>
        <p class="DefaultStyle"><img alt="Update Image" src="{{skin url=images/update_stock4.png}}" /></p>
        <p class="DefaultStyle"><b><i>* SETELAH UPDATE STOCK BIMETAL1 BERJUMLAH 20, BIMETAL2 BERJUMLAH 25, DAN SETERUSNYA</i></b></p>
    </li>
</ol>
<ol start="5">
    <li>
    <p>Simpan file dan mohon untuk tidak mengubah format file (*.csv).</p>
    </li>
</ol>
<ol start="6">
    <li>
    <p>Attach file *.csv anda dengan menekan tombol Browse.</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_stock6.png}}" /></p>
<ol start="7">
    <li>
        <p>Pastikan file yang dipilih adalah benar, lalu tekan Submit.</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_stock7.png}}" /></p>
<ol start="8">
    <li>
        <p>Produk Anda telah sukses diperbarui.</p>
    </li>
</ol>
<p><img alt="Update Image" src="{{skin url=images/update_stock8.png}}" /></p>
<ol start="9">
    <li>
        <p>Apabila Anda memiliki pertanyaan atau kesulitan, silahkan hubungi kami di <a href="mailto:vendor@klikMRO.com">vendor@klikMRO.com</a> atau 021-5829051.</p>
    </li>
</ol>
<p>&nbsp;</p>
EOF;


if($cmsPage->getId()){
    $cmsPage->setTitle('How to Update stock');
}

$cmsPage->setStores(0)
        ->setIdentifier('how-to-update-stock')
        ->setContent($pageContent)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

$installer->endSetup();