<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Gallery</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="../plugins/ekko-lightbox/ekko-lightbox.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  </head>
  <body>
  <div class="wrapper">
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <div class="b-e-c">

        <div class="b-e-f wnd-filter-container">
          <div class="b-e-f-c">
            <h3 class="b-e-f-title">Kategorie</h3>
            <ul class="ef-level-1   ">
              <li class="b-e-f-i">
                <a href="/" rel="nofollow" class="wnd-link selected">Wszystkie produkty</a>

              </li><li class="b-e-f-i">
                <a href="/?collection=buty-turystyczne" rel="nofollow" class="wnd-link ">Buty turystyczne</a>

              </li><li class="b-e-f-i">
                <a href="/?collection=buty-do-biegania" rel="nofollow" class="wnd-link ">Buty do biegania</a>

              </li><li class="b-e-f-i">
                <a href="/?collection=buty-do-cwiczen-sportowych" rel="nofollow" class="wnd-link ">Buty do ćwiczeń sportowych</a>

              </li><li class="b-e-f-i">
                <a href="/?collection=buty-do-tenisa" rel="nofollow" class="wnd-link ">Buty do tenisa</a>

              </li>
            </ul>
            <div class="b-e-f-select">
              <div class="cf">
                <div class="select">
                  <select class="wnd-filter-select"><option value="/" selected="">Wszystkie produkty</option><option value="/?collection=buty-turystyczne">Buty turystyczne</option><option value="/?collection=buty-do-biegania">Buty do biegania</option><option value="/?collection=buty-do-cwiczen-sportowych">Buty do ćwiczeń sportowych</option><option value="/?collection=buty-do-tenisa">Buty do tenisa</option></select>
                </div>
              </div>
            </div>
          </div>
        </div>
    </aside>
    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card card-primary">
               <!-- <div class="card-header">
                  <h4 class="card-title">Witamy na stronie sklepu internetowego XYZ!</h4>
                </div> -->
                <div class="card-body">
                  <div class="row">
                    <?php
                    require_once "../scripts/dbconnect.php";

                    $sql = "SELECT * FROM `products`";
                    $result = $conn->query($sql);
                    while ($product = $result->fetch_assoc())
                    {
                      echo <<< GET_PRODUCTS_FROM_DB
                    <div class="col-sm-2">
                        <a href="https://phpfinal.robtherobot.space/pages/examples/e-commerce.html?product=$product[product_id]" data-toggle="lightbox" data-title="$product[product_id]" data-gallery="gallery">
                                <img src="$product[picture_link]" class="img-fluid mb-2" alt="Zdjęcie produktu $product[product_id]">
                        </a>
                    </div>
GET_PRODUCTS_FROM_DB;
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  </body>
</html>

