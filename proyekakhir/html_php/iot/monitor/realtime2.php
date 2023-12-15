<!DOCTYPE HTML>
<html>

<head>
    <style>
        #chartContainer {
            height: 250px;
            max-width: 520px;
            margin: 20px auto;
            border: 5px solid black;
            border-radius: 5px;
        }

        .suhu {
            background-color: rgba(225, 225, 225, 0.7);
            position: relative;
            z-index: 1;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>

<body>
    <br><br><br>
    <!-- menampilkan grafik dengan id chartContainer -->
    <!-- ukuran grafik: tinggi = 550 piksel, dan maksimal lebar 920 piksel -->
    <div id="chartContainer" style="height: 250px; max-width: 520px; margin: 0px auto;"></div>

    <h2 style="text-align: center">Monitoring Kelembaban Real Time</h2>

    <!-- import libaray canvasjs dan jquery dengan cdn  -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <div class="suhu">
        <script>

            function beep() {
                var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
                snd.play();

            }


            window.onload = function () {
                //inisialisasi data array dps kepanjangan dari datapoints
                var dps = [];
                var dataLength = 10; // panjang data yang ditampilkan (horizontal), ditampilkan di bagian bawah grafik
                var updateInterval = 1000; //setiap 1,5 dtk data direfresh
                var xVal = 0;
                var yVal = 0;

                //inisialisasi chart js
                var chart = new CanvasJS.Chart("chartContainer", {
                    title: {
                        text: "Grafik Kelembaban Realtime" //memberi judul grafik
                    },
                    data: [{
                        type: "stepLine", //tipe grafik yang digunakan, lihat di situsnya untuk lihat gaya lain
                        markerType: "triangle",  //"circle", "square", "cross", "none"\
                        lineColor: "pink",
                        dataPoints: dps, //dps adalah data yang digunakan
                    }]
                });

                var updateChart = function (count) {
                    // data json diambil pada alamat /getdata
                    $.getJSON("http://localhost/proyek/proyekakhir/html_php/iot/monitor/getdata2.php", function (data) {
                        // $.getJSON("https://api.coindesk.com/v1/bpi/currentprice.json", function (data) {
                        // var kelembaban = data.bpi.USD.rate_float //mengambil data spesifik rate_float
                        var kelembaban = data.kelembaban //mengambil data spesifik rate_float
                        if (kelembaban < 30) {
                            document.getElementById("indikator").textContent = "Kelembaban : " + kelembaban.toString() + " KERING : Aman ";
                            // document.getElementById("huruf").color="GREEN"; 

                        }

                        if (kelembaban >= 30 && kelembaban < 60) {
                            document.getElementById("indikator").textContent = "Tingkat Kelembaban :" + kelembaban.toString() + " NORMAL : * ";
                            // document.getElementById("huruf").color="BLUE"; 

                        }

                        if (kelembaban >= 60 && kelembaban < 70) {
                            document.getElementById("indikator").textContent = "Kelembaban : " + kelembaban.toString() + " MULAI BANYAK UAP AIR : ** "; beep();
                        }
                        if (kelembaban >= 70) {
                            document.getElementById("indikator").textContent = "Kelembaban : " + kelembaban.toString() + " TERDAPAT BANYAK UAP AIR : *** "; beep(); beep(); beep();
                        }





                        console.log(kelembaban) //menampilkan data dengan console.log hanya terlihat saat mode inspect element
                        yVal = kelembaban // mengisi variabel yVal dengan data usd
                        count = count || 1;

                        //melakukan perulangan data dengan for agar data dapat dijalankan
                        for (var j = 0; j < count; j++) {
                            dps.push({
                                x: xVal,
                                y: yVal
                            });
                            xVal++;
                        }

                        //jika datapoints telah melewati datalength
                        if (dps.length > dataLength) {
                            dps.shift(); //maka hapus data awal dengan fungsi shift()
                        }

                    })
                    chart.render();
                };

                //jalankan fungsi updateChart di atas
                updateChart(dataLength);

                //fungsi agar data dapat diupdate setiap 1000 detik sekali
                setInterval(function () {
                    updateChart()
                }, updateInterval);
            }

        </script>
        <center>
            <div id=indikator> <b> NORMAL </b> </div>
        </center>
    </div>
</body>

</html>