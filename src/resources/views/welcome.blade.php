<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Landing Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #c6f5e5;
            transition: background-color 0.3s ease-in-out;
        }

        header {
            background: #206d40;
            color: #ffffff;
            padding-top: 20px;
            min-height: 70px;
            border-bottom: #18441f 4px solid;
            transition: background-color 0.3s ease-in-out;
        }

        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
            transition: color 0.3s ease-in-out;
        }

        header a:hover {
            color: #e8491d;
            transition: color 0.3s ease-in-out;
        }

        .main {
            padding: 80px 0;
            text-align: center;
        }

        .main h1,
        .main h2 {
            color: #333333;
        }

        .main p {
            font-size: 18px;
            color: #555555;
            line-height: 1.6em;
        }

        .main a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e8491d;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .main a.button:hover {
            background-color: #333333;
            transition: background-color 0.3s ease-in-out;
        }
        .jumbotron {
            margin: 15px 30px 0px 30px;
            border-radius: 10px;
            background:
                linear-gradient(rgba(0, 0, 250, 0.25),
                    rgba(125, 250, 250, 0.45)),
                url(https://source.unsplash.com/1600x1050/?dog);
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white !important;
            height: auto;
        }
    </style>
</head>

<body>

    <header class="container-fluid">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#">
                    <img class="rounded-circle"
                        src="https://fbcd.co/images/products/3eac7432730321c3aac109b495f272d6_resize.jpg" alt="Logo"
                        height="30">
                </a>
                <h6>PetaConnect</h6>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fab fa-facebook"></i> Facebook</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fab fa-instagram"></i> Instagram</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fab fa-tiktok"></i> TikTok</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="jumbotron jumbotron-fluid">
        <div class="container" style="height: 50vh;">
            <h1 class="display-4">Peta Connect</h1>
            <p class="lead">Salam Hangat dari Kami</p>
        </div>

    </div>

    <div class="main container">
        <h1 class="display-4">Aipet Matchmaking Server</h1>
        <h2 class="lead">Tentukan Anjing Peliharaanmu</h2>
        <p class="lead">Buatlah Arti Persahabatan Hidup</p>
        <a href="#" class="btn btn-primary btn-lg">Learn More</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
</body>