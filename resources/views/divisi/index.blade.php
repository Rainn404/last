<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Divisi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6ff;
            padding: 30px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
        }

        .card-divisi {
            width: 330px;
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .card-divisi:hover {
            transform: translateY(-10px);
        }

        .card-header {
            background: linear-gradient(135deg, #3783ff, #5ea9ff);
            padding: 35px;
            border-radius: 15px;
            color: white;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .ketua {
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        .deskripsi {
            margin-top: 15px;
            font-size: 14px;
            color: #444;
            line-height: 1.4;
        }
    </style>

</head>
<body>

<h2 style="text-align:center;">Divisi HIMA-TI</h2>

<div class="container">

    @foreach ($divisis as $d)
        <div class="card-divisi">
            <div class="card-header">
                {{ $d->nama_divisi }}
            </div>

            <div class="ketua">
                Ketua: {{ $d->ketua_divisi }}
            </div>

            <div class="deskripsi">
                {{ $d->deskripsi }}
            </div>
        </div>
    @endforeach

</div>

</body>
</html>