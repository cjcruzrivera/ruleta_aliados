<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premio Ganado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            padding: 20px;
        }
        .mensaje {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #666666;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .imagen-premio {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="mensaje">
        <h2>Felicidades, <?php echo $nombre_participante; ?>!</h2>
        <p>Has ganado el premio: <?php echo $premio; ?></p>
        <img src="<?php echo substr($url_img, 4); ?>" alt="Imagen del premio" class="imagen-premio">
    </div>
</body>
</html>
