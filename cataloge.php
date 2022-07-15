<!DOCTYPE html>
<html lang="ru" dir="ltr">
<head>
  <meta charset="utf-8">

  <?php require_once('dependence.php'); ?>

  <!-- Bootstrap DatePicker -->

  <style media="screen">
  body {
    background: url(http://random.imagecdn.app/1920/1080) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }

</style>
<title>Каталог</title>
</head>
<body>
  <br>
  <br>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="https://awishlist.herokuapp.com">awishlist</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="https://awishlist.herokuapp.com/cataloge.php ">Каталог</a>
        </li>
      </ul>
      <?php if ( isset ($_SESSION['logged_user']) ) :
        ?>
        <ul class="navbar-nav  mx-md-n20">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <?php echo "Добро пожаловать, ". $_SESSION['logged_user']->login; ?>
              <i class="fa fa-fw fa-user"></i>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="https://awishlist.herokuapp.com/upload.php ">
                <i class="fa fa-fw fa-list-alt"></i>
                Мои списки желаний
              </a>
              <a class="dropdown-item" href="https://awishlist.herokuapp.com/userProfile.php ">
                <i class="fa fa-fw fa-address-card"></i>
                Настройки профиля
              </a>
              <a class="dropdown-item" href="https://awishlist.herokuapp.com/logout.php ">
                <i class="fa fa-fw fa-sign-out-alt"></i>
                Выйти
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  <?php else :?>
    <ul class="navbar-nav ">
      <li class="nav-item">
        <a class="nav-link" href="https://awishlist.herokuapp.com/signup.php ">
          <i class="fa fa-fw fa-user-plus"></i>
          Регистрация
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ">
      <li class="nav-item">
        <a class="nav-link" href="https://awishlist.herokuapp.com/login.php ">
          <i class="fa fa-fw fa-sign-in-alt"></i>
          Авторизация
        </a>
      </li>
    </ul>
  </div>
</nav>
<?php endif; ?>
<br>
<br>
<br>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <?php
      if ( isset ($_SESSION['logged_user'])=="false")
      {
        $user_login_int=$_SESSION['logged_user']->id;
        $countUserWishlist=R::count('newwishlists',"user_login_id=?",array($user_login_int));
        if ($countUserWishlist<=0)
        {
          echo '
          <div class="alert alert-info col-md-8 offset-md-2 alert-dismissible fade show" role="alert" text-center>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4 class="alert-heading">Добавление желаний из каталога</h4>
          <p>Чтобы добавить желания из каталога, Вам нужно создать хотя бы один список желаний </p>
          </div>
          ';
        }
      }
      else
      {
        echo '
        <div class="alert alert-info col-md-8 offset-md-2 alert-dismissible fade show" role="alert" text-center>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Добавление желаний из каталога</h4>
        <p>Чтобы добавить желания из каталога, Вам нужно войти и создать хотя бы один список желаний </p>
        </div>
        ';
      }

      ?>

      <div class="input-bar">
        <div class="input-bar-item width100">
          <form action="https://awishlist.herokuapp.com/cataloge.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
              <input class="form-control width100" value="" name="searchField"  type="text"  placeholder="Поиск по каталогу...">
              <span class="input-group-btn">
                <button type="submit"  name="searchBtn" class="btn btn-outline-dark">Найти
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </input>
          </div>
          <!-- <button type="submit" value="400" name="newBtn">400</button> -->
        </form>
      </div>
    </div>




    <br>
    <?php
    // if (isset($_POST['newBtn'])) {
    //   echo $_POST['newBtn'];
    // }

    $data=$_POST;
    $i=0;
    $products = R::findAll('cataloge');//загрузить все данные из таблицы каталога
    $productsCount=1;

    if (isset($data['searchBtn']))
    {
      if (isset($data['searchField']))
      {
        $productSearchByName=$data['searchField'];

        $products = R::find('cataloge',"product_name like :searchField", array(':searchField' => '%'.$productSearchByName.'%' ));
        $productsCount=R::count('cataloge',"product_name like :searchField", array(':searchField' => '%'.$productSearchByName.'%' ));

        // unset($data['searchBtn']);
        unset($data['searchField']);

      }

    }

    if ($productsCount==0)
    {
      echo '
      <div class="alert alert-info" role="alert">
      <h4 class="alert-heading">К сожалению, по Вашему запросу мы ничего не нашли :(</h4>

      <br>';
        echo '<meta http-equiv="refresh" content="3";url=" https://awishlist.herokuapp.com/cataloge.php "> ';
    }
    elseif($productsCount!=0)
    {
      echo '<div class="card-columns" >';

      foreach ($products as $oneProduct)
      {
        $productID=$oneProduct->id;
        $productName=$oneProduct->product_name;
        $productInfo=$oneProduct->product_info;
        $productPrice=$oneProduct->product_price;
        $productCurrencyType=$oneProduct->product_currency_type;
        $productType=$oneProduct->product_type;
        $productImgPath=$oneProduct->product_img_path;
        $productLink=$oneProduct->product_link;

        echo'

        <div class="card bg-light mb-3 text-center" style="max-width: 20rem;  display: inline-block">


        <div class="text-right p-1">

        <button type="button"  name="moreInfo" class="btn btn-outline-info"   data-toggle="modal" data-target="#exampleModal" data-product_ID='.$productID.' data-product_name='.$productName.' data-product_info="'.$productInfo.'"
        data-product_price='.$productPrice.' data-product_currency_type='.$productCurrencyType.' data-product_type='.$productType.' data-product_link='.$productLink.'>
        Подробнее
        <i class="fa fa-ellipsis-v"></i>
        </button>

        </div>


        <img class="card-img-top w-75 h-75" src="'.$productImgPath.'" alt='.$productName.'>
        <div class="card-header">'.$productName.'</div>
        <div class="card-body">
        <p class="card-text">Цена: '.$productPrice.$productCurrencyType.'</p>


        </div>';

        if ( isset($_SESSION['logged_user']) )
        {
          $user_login_int=$_SESSION['logged_user']->id;
          $wishlistsCount = R::count('newwishlists',"user_login_id=?",array($user_login_int));
          if ($wishlistsCount!=0)
          {
            echo '
            <div class="card-footer bg-transparent">
            <form action="https://awishlist.herokuapp.com/cataloge.php" method="post" enctype="multipart/form-data">


              <select class="form-control" id="exampleFormControlSelect1" name="selectWishlistToAdd" data-toggle="tooltip" data-placement="right" title="Выберите список, в который нужно добавить желание из каталога">
              ';


              $wishlists = R::find('newwishlists',"user_login_id=?",array($user_login_int));

              foreach ($wishlists as $oneWishList){
                $wishlistId=$oneWishList->id;
                $wishlistName=$oneWishList->wishlist_name;
                echo '  <option  value='.$wishlistId.'>'.$wishlistName.'</option>';
              }

              echo '
              </select>
              <br>
              <button id="icon" type="submit"  name="addWishFromCataloge" value='.$productID.' class="btn btn-outline-success btn-block"  data-toggle="tooltip" title="Добавить желание в список">
              <i class="fa fa-plus-circle"></i>
              </button>
              </form>
              </div>
              ';
            }

          }




          echo ' </div>';
          // if ($i%4==0)
          // {
          //   echo '<br>';
          // }


        }


      }


      if (isset($data['addWishFromCataloge']))
      {
        $addProductId=$data['addWishFromCataloge'];


        $loadProductWish = R::load('cataloge', $addProductId);

        $loadProductName=$loadProductWish->product_name;
        $loadProductInfo=$loadProductWish->product_info;
        $loadProductPrice=$loadProductWish->product_price;
        $loadProductCurrencyType=$loadProductWish->product_currency_type;
        $loadProductType=$loadProductWish->product_type;
        $loadProductImgPath=$loadProductWish->product_img_path;
        $loadProductLink=$loadProductWish->product_link;

        $_FILES['file']['name']=$loadProductImgPath;

        $myCopiedFile= basename($_FILES['file']['name']);
        $newNameOfFile = mt_rand(0, 10000) . $myCopiedFile;


        // TODO: посмотреть значение функции  tempnam();

        $myFile='wishsImg/' .$newNameOfFile;

        copy($_FILES['file']['name'], $myFile);

        $addProdToWishlist = R::dispense('wishs');//автоматическое создание таблицы пользователей


        //автоинкремент автоматически создается
        $addProdToWishlist->wish_name = $loadProductName;
        $addProdToWishlist->wish_info = $loadProductInfo;
        $addProdToWishlist->wish_price = $loadProductPrice;

        $addProdToWishlist->wish_wishlist_id=$data['selectWishlistToAdd'];

        $addProdToWishlist->wish_link=$loadProductLink;
        $addProdToWishlist->wish_img_path=$myFile;//загружать ещё и картинку
        $addProdToWishlist->wish_currency_type=$loadProductCurrencyType;
        $addProdToWishlist->wish_was_taken="false";


        R::store($addProdToWishlist);
        unset($data['addWishFromCataloge']);
        //
        echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/cataloge.php "> ';
      }


      ?>




    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="ID_prod_info"></p>
        <p id="ID_prod_type"></p>
        <p id="ID_prod_price"></p>
        <p id="ID_prod_link"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
</div>


</body>
</html>

<script type="text/javascript">
$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var product_name_JS = button.data('product_name')
  var product_info_JS=button.data('product_info');
  var product_link_JS=button.data('product_link');
  var product_currency_type_JS=button.data('product_currency_type')
  var product_price_JS=button.data('product_price');
  var product_type_JS=button.data('product_type');

  var product_ID_JS=button.data('product_ID');

  var modal = $(this)
  modal.find('.modal-title').text(product_name_JS)
  modal.find('#ID_prod_info').html("<b>Подробная информация о товаре: </b>"+product_info_JS)
  modal.find('#ID_prod_price').html("<b>Цена: </b>"+product_price_JS+product_currency_type_JS)
  modal.find('#ID_prod_type').html("<b>Категория товара: </b>"+product_type_JS)
  modal.find('#ID_prod_link').html("<b>Ссылка на оригинал: </b> <a href="+product_link_JS+" target='_blank'>"+product_link_JS+"</a>")

  //
  // $('button[name="addWishFromCataloge"]').click(function () {
  //   // location.href = "http://awishlist/authAndLogin/upload";
  //   modal.find('#ID_prod_info').text(product_name_JS)
  // });


});

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
$('#icon').click(function() {
  $('#icon').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>').addClass('disabled');
});

</script>
