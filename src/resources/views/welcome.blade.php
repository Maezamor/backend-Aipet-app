<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tampilan Opening</title>
 <style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    background: rgb(108, 108, 206)
}

.opening {
    position: relative;
    height: 100vh;
    overflow: hidden;
}

#openingVideo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -1;
}

.opening-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #fff;
}

.opening-content h1 {
    font-size: 3em;
}

.opening-content p {
    font-size: 1.5em;
}

</style>

</head>
<body>
    <div class="opening">
        <video autoplay muted loop id="openingVideo">
            <source src="https://www.youtube.com/watch?v=-ikyKA-8br4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="opening-content">
            <h1>Selamat Datang</h1>
            <p>Website Ini Adalah Tempat yang Luar Biasa</p>
        </div>
    </div>
</body>
</html>