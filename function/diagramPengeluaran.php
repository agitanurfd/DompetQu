<!-- Chart pengeluaran -->
<script type="text/javascript">
    var ctx = document.getElementById("myChart3").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Transportasi", "Konsumsi", "Komunikasi", "Listrik", "Outfit", "Lain - lain"],
            datasets: [{
                label: 'Data Pengeluaran',
                data: [
                    <?php
                    $transportasi = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Transportasi' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    echo mysqli_num_rows($transportasi);
                    ?>,
                    <?php
                    $konsumsi = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Konsumsi' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    echo mysqli_num_rows($konsumsi);
                    ?>,
                    <?php
                    $komunikasi = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Komunikasi' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    echo mysqli_num_rows($komunikasi);
                    ?>,
                    <?php
                    $listrik = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Listrik' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    echo mysqli_num_rows($listrik);
                    ?>,
                    <?php
                    $outfit = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Outfit' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    echo mysqli_num_rows($outfit);
                    ?>,
                    <?php
                    $lain = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Lain - lain' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    echo mysqli_num_rows($lain);
                    ?>
                ],
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)',
                    '#2dc750'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    '#2dc750'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<!-- Chart pengeluaran -->
<script type="text/javascript">
    var ctx = document.getElementById("myChart4").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Transportasi", "Konsumsi", "Komunikasi", "Listrik", "Outfit", "Lain - lain"],
            datasets: [{
                label: 'Data Pengeluaran',
                data: [
                    <?php
                    $transportasi = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Transportasi' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    $transportasiSum = mysqli_num_rows($transportasi);
                    while ($dataTransportasi = mysqli_fetch_assoc($transportasi)) {
                        $jumlahTransportasi[] = $dataTransportasi['jumlah'];
                        $jumlahConvertTransportasi = str_replace('.', '', $jumlahTransportasi);
                        $totalTransportasi = array_sum($jumlahConvertTransportasi);
                    }

                    if ($transportasiSum != null) {
                        echo $totalTransportasi;
                    } elseif ($transportasiSum == null) {
                        echo 0;
                    }
                    ?>,

                    <?php
                    $konsumsi = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Konsumsi' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    $konsumsiSum = mysqli_num_rows($konsumsi);
                    while ($dataKonsumsi = mysqli_fetch_assoc($konsumsi)) {
                        $jumlahKonsumsi[] = $dataKonsumsi['jumlah'];
                        $jumlahConvertKonsumsi = str_replace('.', '', $jumlahKonsumsi);
                        $totalKonsumsi = array_sum($jumlahConvertKonsumsi);
                    }

                    if ($konsumsiSum != null) {
                        echo $totalKonsumsi;
                    } elseif ($konsumsiSum == null) {
                        echo 0;
                    }
                    ?>,

                    <?php
                    $komunikasi = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Komunikasi' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    $komunikasiSum = mysqli_num_rows($komunikasi);
                    while ($dataKomunikasi = mysqli_fetch_assoc($komunikasi)) {
                        $jumlahKomunikasi[] = $dataKomunikasi['jumlah'];
                        $jumlahConvertKomunikasi = str_replace('.', '', $jumlahKomunikasi);
                        $totalKomunikasi = array_sum($jumlahConvertKomunikasi);
                    }

                    if ($komunikasiSum != null) {
                        echo $totalKomunikasi;
                    } elseif ($komunikasiSum == null) {
                        echo 0;
                    }
                    ?>,

                    <?php
                    $listrik = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Listrik' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    $listrikSum = mysqli_num_rows($listrik);
                    while ($dataListrik = mysqli_fetch_assoc($listrik)) {
                        $jumlahListrik[] = $dataListrik['jumlah'];
                        $jumlahConvertListrik = str_replace('.', '', $jumlahListrik);
                        $totalListrik = array_sum($jumlahConvertListrik);
                    }

                    if ($listrikSum != null) {
                        echo $totalListrik;
                    } elseif ($listrikSum == null) {
                        echo 0;
                    }
                    ?>,

                    <?php
                    $outfit = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Outfit' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    $outfitSum = mysqli_num_rows($outfit);
                    while ($dataOutfit = mysqli_fetch_assoc($outfit)) {
                        $jumlahOutfit[] = $dataOutfit['jumlah'];
                        $jumlahConvertOutfit = str_replace('.', '', $jumlahOutfit);
                        $totalOutfit = array_sum($jumlahConvertOutfit);
                    }

                    if ($outfitSum != null) {
                        echo $totalOutfit;
                    } elseif ($outfitSum == null) {
                        echo 0;
                    }
                    ?>,

                    <?php
                    $lain = mysqli_query($koneksi, "SELECT * FROM pengeluaran WHERE username = '$username' AND keterangan='Lain - lain' AND tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'");
                    $lainSum = mysqli_num_rows($lain);
                    while ($dataLain = mysqli_fetch_assoc($lain)) {
                        $jumlahLain[] = $dataLain['jumlah'];
                        $jumlahConvertLain = str_replace('.', '', $jumlahLain);
                        $totalLain = array_sum($jumlahConvertLain);
                    }

                    if ($lainSum != null) {
                        echo $totalLain;
                    } elseif ($lainSum == null) {
                        echo 0;
                    }
                    ?>
                ],
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)',
                    '#2dc750'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    '#2dc750'
                ],
                borderWidth: 1
            }]
        }
    });
</script>