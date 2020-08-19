<!DOCTYPE html>

<?php
  require_once('menu.php');
  require_once('Plantilla6.php');

  $plantilla = new Plantilla();
  $plantilla->setMenu($menu, $submenu, $submenu2);
  $plantilla->setFriendly(false);
  $plantilla->setWebTitle('Ilustraciones internacionales');
  $plantilla->setDirContents('paginas/');

?>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
        
            /*estilos del menu*/
            #menu > ul > li  { /* Menú principal horizontal */
            display:inline-block;
            }
            #menu ul ul { /* Oculta los submenús */
            display:none;
            position:absolute;
            background-color:white;
            border:1px solid black;
            }
            #menu > ul > li:hover ul { /* Mostrar submenú */
            display:block;
            }
        
            /*iniciales*/
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 25px;
            }

            a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">

                    <div id="cabecera">
                        <h1>Comidas rápidas</h1>
                    </div>

                    <div id="menu">
                        <?php echo $plantilla->getMenu(); ?>
                    </div>

                    <div id="contenido">
                        <?php $plantilla->writePageContent(); ?>
                    </div>

                </div> 
            </div>
        </div>
    </body>
</html>
