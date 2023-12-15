<!DOCTYPE HTML>
<html>

<head>
    <!-- import library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>

<body>
    <br><br><br>
    <!-- menampilkan grafik dengan id chartContainer -->
    <!-- ukuran grafik: tinggi = 550 piksel, dan maksimal lebar 920 piksel -->
    <div id="chartContainer" style="height: 250px; max-width: 520px; margin: 0px auto;"></div>

    <h2 style="text-align: center">Monitoring Suhu Real Time</h2>

    <script>
        window.onload = function () {
            var ohlcDataPoints = []; // Data OHLC
            var dps = []; // Data Line Chart
            var dataLength = 10; // panjang data yang ditampilkan (horizontal), ditampilkan di bagian bawah grafik
            var updateInterval = 1000; // setiap 1,5 detik data direfresh
            var xVal = 0;
            var yVal = 0;

            var ctx = document.getElementById('chartContainer').getContext('2d');

            var chart = new Chart(ctx, {
                type: 'bar', // Tipe grafik awal, nanti akan diubah ke kombinasi OHLC dan Line
                data: {
                    datasets: [{
                        label: 'OHLC',
                        data: ohlcDataPoints,
                        type: 'bar', // Jenis grafik OHLC
                        yAxisID: 'y-axis-1', // ID sumbu y untuk OHLC
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Temperature',
                        data: dps,
                        type: 'line', // Jenis grafik Line
                        fill: false,
                        yAxisID: 'y-axis-2', // ID sumbu y untuk Line Chart
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom'
                        },
                        y: [{
                            type: 'linear',
                            display: true,
                            position: 'left',
                            id: 'y-axis-1', // ID sumbu y untuk OHLC
                        }, {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            id: 'y-axis-2' // ID sumbu y untuk Line Chart
                        }]
                    }
                }
            });

            var updateChart = function (count) {
                // data json diambil pada alamat /getdata
                $.getJSON("http://localhost/proyek/proyekakhir/html_php/iot/monitor/getdata.php", function (data) {
                    var suhu = data.suhu;
                    if (suhu < 29) {
                        document.getElementById("indikator").textContent = "Suhu : " + suhu.toString() + " NORMAL : Hemm Bowleh Bowleh ";
                    } else if (suhu >= 29 && suhu < 30) {
                        document.getElementById("indikator").textContent = "Suhu :" + suhu.toString() + " NORMAL : Agak Panas Dikit ";
                    } else if (suhu >= 30 && suhu < 31) {
                        document.getElementById("indikator").textContent = "Suhu : " + suhu.toString() + " PANAS : Lumayan lah ";
                    } else if (suhu >= 31) {
                        document.getElementById("indikator").textContent = "Suhu : " + suhu.toString() + " SANGAT PANAS : Panas Bett ";
                    }

                    yVal = suhu;
                    count = count || 1;

                    ohlcDataPoints.push({ x: xVal, o: yVal, h: yVal + 1, l: yVal - 1, c: yVal });
                    dps.push({ x: xVal, y: yVal });

                    if (ohlcDataPoints.length > dataLength) {
                        ohlcDataPoints.shift();
                    }
                    if (dps.length > dataLength) {
                        dps.shift();
                    }
                });
                chart.update();
            };

            // jalankan fungsi updateChart di atas
            updateChart(dataLength);

            // fungsi agar data dapat diupdate setiap 1000 detik sekali
            setInterval(function () {
                updateChart();
            }, updateInterval);
        }
    </script>
    <center>
        <div id=indikator> <b> NORMAL </b> </div>
    </center>
</body>

</html>
