<?php
/**
 * Author: iCube
 *
 * - Update CMS Page Upload Artikel (Product Upload)
 */
?>
<?php
$installer = $this;
$installer->startSetup();

$cmsPage = Mage::getModel('cms/page')->load('how-to-upload-article', 'identifier');

    $pageContent = <<<EOT
<pre><b>Panduan Mengunggah Artikel (PRODUCT UPLOAD):</b></pre>
<ol start="1">
	<li>
		<p>Siapkan file Excel (*.xls) berisi list artikel yang akan diupload beserta kategori barang tersebut.</p>
	</li>
</ol>
<p><img alt="Update article" src="{{skin url=images/update_article.png}}" /></p>
<ol start="2">
	<li>
		<p>Kirimkan file tersebut ke Tim Merchandising KlikMRO untuk mengklasifikasikan kategori dan dipersiapkan template untuk mengupload artikel. Selanjutnya Tim Merchandising KlikMRO akan mengirimkan template upload artikel (dengan format *.csv) ke vendor.</p>
	</li>
</ol>
<ol start="3">
	<li>
		<p>Isi kolom-kolom kosong yang terdapat pada template tersebut dengan ketentuan-ketentuan yang sudah ditetapkan oleh pihak KlikMRO.</p>
	</li>
</ol>
<p><img alt="Update article" src="{{skin url=images/update_article2.png}}" /></p>
<ol start="4">
	<li>
		<p>Setelah selesai mengisi template, simpan file tanpa mengubah format file&nbsp; tersebut.</p>
	</li>
</ol>
<p><img alt="Update article" src="{{skin url=images/update_article3.png}}" /></p>
<ol start="5">
	<li>
		<p>Akses Vendor Dashboard dengan URL: <a href="http://vendor.klikmro.com/">http://vendor.klikmro.com</a></p>
	</li>
</ol>
<ol start="6">
	<li>
		<p>Masukkan username dan password yang telah diberikan oleh pihak KlikMRO</p>
	</li>
</ol>
<ol start="7">
	<li>
		<p>Pilih menu Product Catalog lalu klik Product Upload.</p>
	</li>
</ol>
<p><img alt="Update article" src="{{skin url=images/update_article4.png}}" /></p>
<ol start="8">
	<li>
		<p>Attach file Anda dengan menekan tombol Choose File. Pastikan file sudah terpilih dengan benar, lalu tekan tombol Submit.</p>
	</li>
</ol>
<p><img alt="Update article" src="{{skin url=images/update_article5.png}}" /></p>
<ol start="9">
	<li>
		<p>Artikel yang sudah diupload akan muncul di menu Product List.</p>
	</li>
</ol>
<p><img alt="Update article" src="{{skin url=images/update_article6.png}}" /></p>
<ol start="10">
	<li>
		<p>Lengkapi artikel yang sudah diupload dengan harga, stok dan gambar. Panduannya dapat dilihat di file pdf berikutnya.</p>
	</li>
</ol>
<ol start="11">
	<li>
		<p>Apabila Anda memiliki pertanyaan atau kesulitan, dapat menghubungi kami di <a href="mailto:vendor@klikmro.com">vendor@klikmro.com</a> atau 021-5829051.</p>
	</li>
</ol>
EOT;

// if($cmsPage->getId()){
//     $cmsPage->setTitle('How to Upload Article');
// }

$cmsPage->setStores(0)
		->settitle('How to Upload Article')
        ->setIdentifier('how-to-upload-article')
        ->setContent($pageContent)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

$installer->endSetup();
