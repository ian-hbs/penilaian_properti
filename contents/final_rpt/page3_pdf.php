<?php
	$pdf->AddPage();

	$pdf->Image('../../uploads/logo/01.jpg',16,6,12);

	$pdf->SetFont('Arial','BU',12);

	$pdf->ln(12);

	$pdf->Cell(0,8,'ASUMSI DAN SYARAT-SYARAT PEMBATASAN',0,1,'C');

	$pdf->ln(5);

	$pdf->SetFont('Arial','',9);

	$list = array(
					'Semua penggugatan / sengketa dan hipotik yang masih berjalan, jika ada dapat diabaikan dan properti yang dinilai bebasdan bersih di bawah tanggung jawab pemilik',
					'Dalam penilaian ini telah diabaikan beberapa item yang menurut hemat kami memiliki nilai yang sangat minimal dan yang umumnya diklasifikasikan sebagai biaya operasional perusahaan.',
					'Jumlah keseluruhan dari properti yang tercantum dalam laporan ini hakekatnya merupakan satu kesatuan nilai, oleh karenanya upaya untuk memisah-misahkan satu atau beberapa nilai aset untuk kepentingan tertentu akan membuat laporan penilaian ini tidak berlaku.',
					'Kami telah memeriksa kondisi properti yang dinilai namun kami tidak berkewajiban untuk memeriksa struktur bangunan ataupun bagian yang tertutup dan tidak terlihat dan bukan tanggung jawab kami sebagai penilai apabila ada pelapukan dan atau kerusakan lain.',
					'Opini nilai properti dalam penilaian ini merupakan cerminan kondisi pasar properti pada saat tanggal penilaian serta kondisi penggunaan dan hunian atas properti tersebut merupakan pengamatan pada saat tanggal tersebut.',
					'Nilai Pasar adalah estimasi sejumlah uang yang dapat diperoleh dari hasil penukaran suatu aset atau liabilitas pada tanggal penilaian, antara pembeli yang berminat membeli dengan penjual yang berminat menjual, dalam suatu transaksi bebas ikatan, yang pemasarannya dilakukan secara layak, di mana kedua pihak masing-masing bertindak atas dasar pemahaman yang dimilikinya, kehati-hatian dan tanpa paksaan (SPI 101).',
					'Nilai Likuidasi adalah sejumlah uang yang mungkin diterima dari penjualan suatu aset dalam jangka waktu yang relatif pendek untuk dapat memenuhi jangka waktu pemasaran dalam definisi Nilai Pasar. Pada beberapa situasi, Nilai Likuidasi dapat melibatkan penjual yang tidak berminat menjual, dan pembeli yang membeli dengan mengetahui situasi yang tidak menguntungkan penjual (SPI 102).',
					'Laporan penilaian ini hanya dapat digunakan secara terbatas sesuai dengan tujuan yang dijelaskan dalam laporan serta ditujukan terbatas kepada klien dimaksud.',
					'Berkaitan dengan penugasan penilaian ini kami tidak melakukan penyelidikan yang berkaitan dengan status hukum kepemilikan, keuangan dan lain sebagainya atas properti tersebut.',
					'Dalam penilaian ini kami berasumsi bahwa seluruh properti didukung oleh dokumen kepemilikan yang sah dan bebas dari sengketa dan atau hipotik.',
					'Baik perusahaan maupun para penilai dan karyawan lainnya sama sekali tidak mempunyai kepentingan finansial terhadap properti yang dinilai.',
					'Biaya untuk penilaian ini tidak tergantung pada besarnya nilai yang tercantum dalam laporan.',
					'Karmanto & Rekan, sehubungan dengan penilaian ini tidak diwajibkan memberi kesaksian atau hadir dalam pengadilan atau instansi pemerintah lainnya yang berhubungan dengan properti yang dinilai kecuali apabila perjanjian telah dibuat sebelumnya.',
					'Laporan ini dianggap tidak sah apabila tidak dicetak di atas kertas berlogo  dan tertera cap KJPP Karmanto & Rekan.',
					'Laporan ini tidak dapat dipublikasikan baik sebagian maupun keseluruhan laporan, referensi di dalam laporan, opini nilai atau nama dan afiliasi penilai tanpa persetujuan dari penilai atau KJPP Karmanto & Rekan.'
				  );
	$no=0;
	foreach($list as $val)
	{
		$no++;
		$pdf->Cell(8,5,$no.'.');
		$pdf->MultiCell(0,5,$val,0,'J');
	}	

	$x = 76;
	$y = $pdf->GetY()+10;

	$pdf->Image('../../assets/images/separator.png',$x,$y,60);

?>