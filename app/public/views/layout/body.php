<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <script src="<?= ASSETS; ?>js/index.js"></script>
    <link rel="stylesheet" href="<?= ASSETS; ?>css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>

    <!-- HEADER -->
    <section class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 py-4">
                    <img src="<?= ASSETS; ?>images/logo.png" alt=""> 
                </div>
            </div>
        </div>
        <div class="titleBkg py-3">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1><?= $title; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /HEADER -->

    <!-- CONTENT -->
    <?= $content; ?>
    <!-- /CONTENT -->

    <?php if($viewHasMenu) : ?>
        <!-- MENU -->
        <div class="menuContain bkgDark">
            <div class="container">
                <div class="row">
                    <div class="col-4 text-center px-0">
                        <div class="menuItem buttonStyle">
                            <a href="/help">Aiuto <i data-feather="help-circle"></i></a>
                        </div>
                    </div>
                    <div class="col-4 text-center px-0">
                        <div class="menuItem buttonStyle">
                            <a href="/logout">Esci <i data-feather="unlock"></i></a>
                        </div>
                    </div>
                    <div class="col-4 text-center px-0">
                        <div class="menuItem buttonStyle">
                            <a href="/"> Elenco <i data-feather="list"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
            <script>feather.replace();</script>
        </div> 
        <!-- /MENU -->
    <?php endif; ?>
    
    <?php if($viewHasFooter) : ?>
        <!-- FOOTER -->
        <section class="footer bkgDark">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        APP by <strong>Monza Mobilità</strong>
                    </div>
                </div>
            </div>
        </section>
        <!-- /FOOTER -->
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>