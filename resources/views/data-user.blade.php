<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/quicksand" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('kalkulator.png') }}" />

    <title>Perhitungan Akar Kuadrat</title>
    <style>
        body {
            font-family: 'Quicksand Book Oblique', sans-serif;
        }

        .mid-content {
            width: 90%;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.56) 0px 22px 70px 4px;
            overflow: auto;
        }

        .nav-shadow {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: top 0.3s;
        }

        .navbar-hidden {
            top: -80px;
        }

        .side-content {
            width: 5%;
        }

        .second-content {
            width: 50%;
        }

        .shadow-result {
            box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg nav-shadow" id="myNavbar">
        <div class="container-fluid">
            <a class="navbar-brand title" style="font-weight: bold" href="#">Aplikasi Perhitungan Akar Kuadrat</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Perhitungan Akar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('data-api') }}">Data Perhitungan Api</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('data-plsql') }}">Data Perhitungan PlSql</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('data-user') }}">Rekapan Data
                            User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="performLogout();" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <form id="logout-func" action="{{ route('logout') }}" method="POST" class="d-none" hidden>
        @csrf
    </form>

    <div class="vh-100 text-center d-flex p-5 mt-5">
        <div class="side-content">
        </div>
        <div class="mid-content bg-white">
            <div class="text-center border border-secondary">
                <h2 class="p-3">Rekapan Data User</h2>

                <div class="container pb-5 mt-5">
                    <table class="table table-sm" id="akarApiTable">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nim</th>
                                <th scope="col">Inputan API</th>
                                <th scope="col">Inputan PlSql</th>
                                <th scope="col">Inputan Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="side-content">
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        function performLogout() {
            var logoutForm = document.getElementById('logout-func');
            if (logoutForm) {
                logoutForm.submit();
            }
        }

        let prevScrollPos = window.pageYOffset;

        window.onscroll = function() {
            const currentScrollPos = window.pageYOffset;

            if (prevScrollPos > currentScrollPos) {
                document.getElementById("myNavbar").style.top = "0";
            } else {
                document.getElementById("myNavbar").style.top = "-80px";
            }

            prevScrollPos = currentScrollPos;
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#akarApiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/api/data-user') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'nim',
                        name: 'nim',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'Api',
                        name: 'Api',
                        searchable: false,
                    },
                    {
                        data: 'Plsql',
                        name: 'Plsql',
                        searchable: false,
                    },
                    {
                        data: 'total',
                        name: 'total',
                        searchable: false,
                    }
                ]
            });
        });

        function fetchData() {
            axios.get('/api/data-user')
                .then(function(response) {
                    console.log(response.data);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        // Panggil fetchData saat halaman dimuat
        document.addEventListener("DOMContentLoaded", fetchData);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1o5c/6RK5QnrF6H3Rdf5cl" crossorigin="anonymous">
    </script>
</body>

</html>
