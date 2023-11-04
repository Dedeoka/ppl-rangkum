<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <title>Perhitungan Akar Kuadrat</title>
</head>

<body>
    <div class="text-center border border-secondary">
        <h1 class="pt-5">Kalkulator Akar Kuadrat</h1>
        <form>
            <p>Masukan Bilangan Yang Ingin Di Akar Kuadratkan</p>
            <div class="justify-content-center">
                <input type="text" name="bilangan" id="bilangan" class="rounded text-center"
                    value="{{ Auth::user()->nim }}">
            </div>
            <div class="mt-3    ">
                <button type="button" id="subApi" class="btn btn-success ml-5">Hitung API</button>
                <button type="button" id="subPlsql" class="btn btn-success ml-5">Hitung PlSql</button>
            </div>
            <div id="bilangan-error" class="text-danger pb-5"></div>
        </form>

        <div class="w-25 justify-content-center" style="margin: 0 auto;" id="container">
            <!-- Tampilkan Hasil -->
            <div id="hasil" class="pb-3 fw-bold">
                <!-- Hasil akan ditampilkan di sini -->
            </div>
            <!-- Tampilkan Waktu Eksekusi -->
            <div id="execution-time" class="pb-3 fw-bold">
                <!-- Waktu eksekusi akan ditampilkan di sini -->
            </div>
        </div>

        <div id="nim" hidden>
            <input type="text" id="nim" value="{{ Auth::user()->nim }}">
        </div>

        <div class="container pb-5">
            <h1 class="mt-5 mb-5 border-bottom">Data Hasil Akar Kuadrat</h1>
            <table class="table table-sm" id="akarApiTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nim</th>
                        <th scope="col">Bilangan</th>
                        <th scope="col">Hasil Akar</th>
                        <th scope="col">Metode</th>
                        <th scope="col">Waktu (detik)</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#akarApiTable').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ url('/api/akar-kuadrat-api') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                }, {
                    data: 'nim',
                    name: 'nim',
                    searchable: false,
                    orderable: false,
                }, {
                    data: 'bilangan',
                    name: 'bilangan',
                    searchable: false,
                    orderable: false,
                }, {
                    data: 'akar_kuadrat',
                    name: 'akar_kuadrat',
                    searchable: false,
                    orderable: false,
                }, {
                    data: 'metode',
                    name: 'metode',
                }, {
                    data: 'waktu',
                    name: 'waktu',
                }]
            });
        });
    </script>
    <script>
        // Mengambil elemen form dan div waktu eksekusi
        var executionTimeDiv = document.getElementById('execution-time');
        $(document).ready(function() {
            var nim = document.querySelector('input[id="nim"]').value;
            $("#subPlsql").click(function() {
                var bilangan = document.querySelector('input[id="bilangan"]').value;
                axios.post('/api/akar-kuadrat-plsql', {
                        bilangan: bilangan,
                        nim: nim
                    })
                    .then(function(response) {
                        // Menghentikan timer
                        // Menampilkan waktu eksekusi di dalam div waktu eksekusi
                        const container = document.getElementById('container');
                        container.style.border = '1px solid';
                        executionTimeDiv.innerHTML = 'Waktu Eksekusi: ' + response.data.waktu_eksekusi +
                            ' detik';

                        // Menampilkan hasil bilangan terakhir dan hasil kuadratnya
                        var bilanganTerakhir = response.data.bilangan_terakhir;
                        var hasilKuadrat = response.data.hasil_kuadrat;
                        var hasilElement = document.getElementById('hasil');
                        hasilElement.innerHTML = '<br>Hasil Perhitungan: ' + hasilKuadrat;
                        $('#akarApiTable').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        // Menampilkan pesan validasi error jika ada
                        if (error.response && error.response.status === 422) {
                            var errors = error.response.data;
                            if (errors.bilangan) {
                                var bilanganErrorDiv = document.getElementById('bilangan-error');
                                bilanganErrorDiv.textContent = errors.bilangan[0];
                            }
                        } else {
                            console.log(error);
                        }
                    });
            });
        });

        $(document).ready(function() {
            var nim = document.querySelector('input[id="nim"]').value;
            $("#subApi").click(function() {
                var bilangan = document.querySelector('input[name="bilangan"]').value;
                axios.post('/api/akar-kuadrat-api', {
                        bilangan: bilangan,
                        nim: nim
                    })
                    .then(function(response) {
                        // Menghentikan timer
                        // Menampilkan waktu eksekusi di dalam div waktu eksekusi
                        const container = document.getElementById('container');
                        container.style.border = '1px solid';
                        executionTimeDiv.innerHTML = 'Waktu Eksekusi: ' + response.data.waktu_eksekusi +
                            ' detik';

                        // Menampilkan hasil bilangan terakhir dan hasil kuadratnya
                        var bilanganTerakhir = response.data.bilangan_terakhir;
                        var hasilKuadrat = response.data.hasil_kuadrat;
                        var hasilElement = document.getElementById('hasil');
                        hasilElement.innerHTML = '<br>Hasil Perhitungan: ' + hasilKuadrat;
                        $('#akarApiTable').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        // Menampilkan pesan validasi error jika ada
                        if (error.response && error.response.status === 422) {
                            var errors = error.response.data;
                            if (errors.bilangan) {
                                var bilanganErrorDiv = document.getElementById('bilangan-error');
                                bilanganErrorDiv.textContent = errors.bilangan[0];
                            }
                        } else {
                            console.log(error);
                        }
                    });
            });
        });
        // // Menambahkan event listener untuk menghandle submit form
        // form.addEventListener('submit', function(event) {
        //     event.preventDefault(); // Mencegah submit form

        //     // Mengambil bilangan dari input form
        //     var bilangan = document.querySelector('input[name="bilangan"]').value;
        //     var nim = document.querySelector('input[id="nim"]').value;


        //     // Mengirim permintaan POST ke API
        //     // Setelah permintaan POST berhasil, tambahkan kode berikut untuk memperbarui tabel
        //     axios.post('/api/akar-kuadrat-api', {
        //             bilangan: bilangan,
        //             nim: nim
        //         })
        //         .then(function(response) {
        //             // Menghentikan timer
        //             // Menampilkan waktu eksekusi di dalam div waktu eksekusi
        //             const container = document.getElementById('container');
        //             container.style.border = '1px solid';
        //             executionTimeDiv.innerHTML = 'Waktu Eksekusi: ' + response.data.waktu_eksekusi +
        //                 ' detik';

        //             // Menampilkan hasil bilangan terakhir dan hasil kuadratnya
        //             var bilanganTerakhir = response.data.bilangan_terakhir;
        //             var hasilKuadrat = response.data.hasil_kuadrat;
        //             var hasilElement = document.getElementById('hasil');
        //             hasilElement.innerHTML = '<br>Hasil Perhitungan: ' + hasilKuadrat;
        //             $('#akarApiTable').DataTable().ajax.reload();
        //         })
        //         .catch(function(error) {
        //             // Menampilkan pesan validasi error jika ada
        //             if (error.response && error.response.status === 422) {
        //                 var errors = error.response.data;
        //                 if (errors.bilangan) {
        //                     var bilanganErrorDiv = document.getElementById('bilangan-error');
        //                     bilanganErrorDiv.textContent = errors.bilangan[0];
        //                 }
        //             } else {
        //                 console.log(error);
        //             }
        //         });
        // });
    </script>
    <script></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1o5c/6RK5QnrF6H3Rdf5cl" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
        < /scri> < /
        body >

            <
            /html>
