<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: linear-gradient(90deg, #000080, #000);
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        .title {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .lead {
            font-size: 1.5rem;
            margin-bottom: 40px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 20px;
            margin-top: 50px;
        }

        .card-title {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .card-text {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            font-size: 1.2rem;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        /* Animación de objetos que se mueven */
        @keyframes moveObjects {
            0% {
                transform: translateY(-50px);
            }

            50% {
                transform: translateY(50px);
            }

            100% {
                transform: translateY(-50px);
            }
        }

        .moving-object {
            animation: moveObjects 5s infinite;
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
        }

        .object1 {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .object2 {
            top: 40%;
            left: 30%;
            animation-delay: 1s;
        }

        .object3 {
            top: 60%;
            left: 50%;
            animation-delay: 2s;
        }

        .object4 {
            top: 80%;
            left: 70%;
            animation-delay: 3s;
        }

        /* Estilos para los nombres */
        .names {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .name {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="moving-object object1"></div>
        <div class="moving-object object2"></div>
        <div class="moving-object object3"></div>
        <div class="moving-object object4"></div>
        <h1 class="title">MJJ-E-Store-system</h1>
        <p class="lead">Bienvenido al API MJJ-E-Store-system. Esta es la ruta principal para consumirla.</p>
        <div class="card">
            <h2 class="card-title">Información</h2>
            <p class="card-text">API en desarrollo, posibles fallas en su consumo, recuerde utilizar buenas prácticas para su óptimo uso</p>
            <div class="names">
                <div class="name">Josue Cotera</div>
                <div class="name">Elberth Martin</div>
                <div class="name">Johan Abad</div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

