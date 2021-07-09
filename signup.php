<!DOCTYPE html>
<html lang="ru" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php require_once 'dependence.php';
	?>


	<title>Регистрация</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<style>
body {
	background: url(images-background/newyear.png) no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
.registrationForm {
	background: #007E3E; /* Цвет фона */
	color: #fff; /* Цвет текста */
	padding: 10px; /* Поля вокруг текста */
	border-radius: 5px; /* Уголки */
}
</style>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
	</div>
</nav>

<?php


$data = $_POST;

//если кликнули на button
if ( isset($data['do_signup']) )
{
	// проверка формы на пустоту полей

	$errors = array();
	if ( trim($data['surname']) == '' )//trim-обрезка ненужных пробелов
	{
		$errors[] = 'Введите фамилию';
	}
	if (preg_match("/[0-9]+/",$data['surname']))
	{
		$errors[] = 'Фамилия не может содержать цифры';
	}
	else
	{
		if (strlen($data['surname'])>50)
		{
			$errors[] = 'Фамилия содержит более 50 символов';
		}
	}

	if ( trim($data['name']) == '' )//trim-обрезка ненужных пробелов
	{
		$errors[] = 'Введите имя';
	}

	if (preg_match("/[0-9]+/",$data['name']))
	{
		$errors[] = 'Имя не может содержать цифры';
	}
	else
	{
		if (strlen($data['name'])>50)
		{
			$errors[] = 'Имя содержит более 50 символов';
		}
	}

	if (preg_match("/[0-9]+/",$data['middlename']))
	{
		$errors[] = 'Отчество не может содержать цифры';
	}
	else
	{
		if (strlen($data['middlename'])>50)
		{
			$errors[] = 'Отчество содержит более 50 символов';
		}
	}

	if ( trim($data['login']) == '' )//trim-обрезка ненужных пробелов
	{
		$errors[] = 'Введите логин';
	}
	if (strlen($data['login'])>20)
	{
		$errors[] = 'Логин содержит более 20 символов';
	}
	if ( trim($data['email']) == '' )
	{
		$errors[] = 'Адрес электронной почты email';
	}
	else
	{
		if (!filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL))
		{
			$errors[]='Адрес электронной почты указан неверно';
		}
	}


	if ( $data['password'] == '' )
	{
		$errors[] = 'Введите пароль';
	}

	if ( $data['passwordRepeat'] != $data['password'] )
	{
		$errors[] = 'Повторный пароль введен неверно!';
	}

	//проверка на существование одинакового логина
	if ( R::count('users', "login = ?", array($data['login'])) > 0)
	{
		$errors[] = 'Пользователь с таким логином уже существует!';
	}

	//проверка на существование одинакового email
	if ( R::count('users', "email = ?", array($data['email'])) > 0)
	{
		$errors[] = 'Пользователь с таким email уже существует!';
	}
	$error = true;
	$secret = '6LdM2_QUAAAAAAMioaLtkyiYP-BoHxD9NYNE1rUf';

	if (!empty($_POST['g-recaptcha-response'])) {
		$out = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
		$out = json_decode($out);
		if ($out->success == true) {
			$error = false;
		}
	}

	if ($error) {
		$errors[] = 'Ошибка заполнения капчи.';
	}



	if ( empty($errors) )
	{

		//ошибок нет, теперь регистрируем
		$user = R::dispense('users');//автоматическое создание таблицы пользователей
		//автоинкремент автоматически создается
		$user->surname = $data['surname'];
		$user->name = $data['name'];
		$user->middlename = $data['middlename'];
		$user->login = $data['login'];
		$user->email = $data['email'];
		$user->password = password_hash($data['password'], PASSWORD_DEFAULT); //пароль нельзя хранить в открытом виде, мы его шифруем при помощи функции password_hash для php > 5.6
		R::store($user);
		//хэширование back crypt, надежднее md5
		//	echo "string";
		// echo '<div style="color:dreen;">Вы успешно зарегистрированы!</div><hr>';
		echo '<meta http-equiv="refresh" content="0;url= https://awishlist.herokuapp.com/login.php"> ';
	}else
	{
		echo '
		<div class="alert alert-danger col-md-6 offset-md-3 alert-dismissible fade show" role="alert" text-center id="errors ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4 class="alert-heading">Ошибка регистрации!</h4>
		<p>'.array_shift($errors).'</p>
		</div>
		';
		//	echo '<div id="errors" style="color:red;">' .array_shift($errors). '</div><hr>';//вывод первой ошибки из массива errors
	}

}
?>
<br>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4 offset-md-4 text-center registrationForm">
			<h2>Регистрация</h2>
			<form action="https://awishlist.herokuapp.com/signup.php " method="POST">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
					</div>
					<input type="text" name="surname" class="form-control" placeholder="Введите фамилию" value="<?php echo @$data['surname']; ?>">
				</div>
				<br>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
					</div>
					<input type="text" name="name" class="form-control" placeholder="Введите имя" value="<?php echo @$data['name']; ?>"><br/>
				</div>
				<br>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-info"></i></span>
					</div>
					<input type="text" name="middlename" class="form-control" placeholder="Введите отчество" value="<?php echo @$data['middlename']; ?>"><br/>
				</div>
				<br>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-user"></i></span>
					</div>
					<input type="text" name="login" class="form-control" placeholder="Введите логин" value="<?php echo @$data['login']; ?>"><br/>
				</div>
				<br>
				<div class="input-group rounded">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-at"></i></span>
					</div>
					<input type="email" name="email" class="form-control" placeholder="Введите адрес электронной почты"  value="<?php echo @$data['email']; ?>"><br/>
				</div>
				<br>
				<div class="input-group rounded ">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-key"></i></span>
					</div>
					<input type="password" name="password" class="form-control" placeholder="Введите пароль" value="<?php echo @$data['password']; ?>"><br/>
				</div>
				<br>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white"><i class="fa fa-redo"></i></span>
					</div>
					<input type="password"  name="passwordRepeat" class="form-control" placeholder="Повторите пароль"   value="<?php echo @$data['passwordRepeat']; ?>"><br/>
				</div>
				<br>
				<div class="g-recaptcha" data-sitekey="6LdM2_QUAAAAAHx20W11zR-vufi6wucxiu_Q1THH" style="display: inline-block;"></div>
				<button class="btn btn-success  btn-block" type="submit" name="do_signup" id="do_signup">Зарегистрироваться</button>
			</form>
		</div>
	</div>
</div>
</body>
</html>

<script type="text/javascript">
$('#do_signup').click(function() {
	$('#do_signup').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Пожалуйста, подождите...').addClass('disabled');
});
</script>
