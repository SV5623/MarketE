<!DOCTYPE html>
<html>
<head>
    <title>Online Store for New Year and Christmas Decorations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <script src="static/js/jquery-3.4.1.min.js"></script>
    <script src="static/js/slick.js"></script>
    <script src="static/js/script.js"></script>
    <link href="static/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="static/css/slick.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <link href="static/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="vein"></div>
    <div class="main container">
        <header>
            <div class="mobile-menu-open-button js_mobile_menu_open_button"><i class="fas fa-bars"></i></div>
            <nav class="js_wide_menu">
                <i class="fas fa-times close-mobile-menu js_close_mobile_menu"></i>
                <div class="wrapper-inside">
                    <div class="visible-elements">
                        <span>Home</span>
                        <span>New Year</span>
                        <span>Christmas</span>
                        <span>Promotions</span>
                        <span>Payment</span>
                        <span>Delivery</span>
                        <span>Reviews</span>
                        <span>About Us</span>
                    </div>
                </div>
            </nav>
            <div class="slider-block">
                <div class="nav-left"><i class="fas fa-chevron-left"></i></div>
                <div class="slider">
                    <div style="background: url('static/img/slide-1.jpg') no-repeat; background-size: auto 100%; background-position: center; background-position-y: 0;">
                        <span class="text-box">New Year decorations with a 30% discount</span>
                    </div>
                    <div style="background: url('static/img/slide-2.jpg') no-repeat; background-size: auto 100%; background-position: center; background-position-y: 0;">
                        <span class="text-box">Wide selection of Christmas wreaths</span>
                    </div>
                    <div style="background: url('static/img/slide-3.jpg') no-repeat; background-size: auto 100%; background-position: center; background-position-y: 0;">
                        <span class="text-box">Give your child a holiday, invite Santa Claus!</span>
                    </div>
                </div>
                <div class="nav-right"><i class="fas fa-chevron-right"></i></div>
            </div>
        </header>
        <section class="product-box">
            <h2>Catalog</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3 product-parent" data-id="<?=$product['id']?>">
                        <div class="product">
                            <div class="product-pic" style="background: url('<?=$product['image']?>') no-repeat; background-size: auto 100%; background-position: center"></div>
                            <span class="product-name"><?=$product['name']?></span>
                            <span class="product_price"><?=$product['price']?> USD</span>
                            <button class="js_buy">Buy</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <footer>
            2019 © Happy New Year!
        </footer>
    </div>
    <div class="overlay js_overlay"></div>
    <div class="popup">
        <h3>Order Form</h3><i class="fas fa-times close-popup js_close-popup"></i>
        <div class='js_error'></div>
        <input type="hidden" name="product-id">
        <input type="text" name="fio" placeholder="Your Name">
        <input type="text" name="phone" placeholder="Phone">
        <input type="text" name="email" placeholder="Email">
        <textarea placeholder="Comment" name="comment"></textarea>
        <button class="js_send">Submit</button>
    </div>
</body>
</html>
