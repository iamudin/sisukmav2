

<!DOCTYPE html>
<html>

<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<link href="https://bengkaliskab.go.id/favicon.png" rel="icon">
	<title>Login - Aplikasi Survei Kepuasan Masyarakat</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <style>
        	/* Coded with love by Mutiullah Samim */
		body,
		html {
			margin: 0;
			padding: 0;
			height: 100%;
			background-image: linear-gradient(to right bottom, #00963d, #008162, #006972, #005068, #1c374c, #1c3245, #1c2e3d, #1b2936, #003443, #004045, #004a39, #005221);
		}
		.user_card {
			height: 400px;
			width: 350px;
			margin-top: auto;
			margin-bottom: auto;
			background: #f39c12;
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px;

		}
		.brand_logo_container {
			position: absolute;
			height: 170px;
			width: 170px;
			top: -75px;
			border-radius: 50%;
			background: #60a3bc;
			padding: 10px;
			text-align: center;
		}
		.brand_logo {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 2px solid white;
		}
		.form_container {
			margin-top: 100px;
		}
		.login_btn {
			width: 100%;
			background: #c0392b !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
		}
		.input-group-text {
			background: #c0392b !important;
			color: white !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user,
		.input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #c0392b !important;
		}
    </style>
</head>
<!--Coded with love by Mutiullah Samim-->
<body>
	<div class="container h-100">

		<div class="d-flex justify-content-center h-100">

			<div class="user_card" style="background:none;border:none;box-shadow:none">
			<center><img src="{{url('logo.png')}}"  alt="Logo">
		<br>
		<br>
		<h3 class="text-white" style="font-weight:bold;text-shadow:0 1px 3px #000">S I S U K M A <sup style="font-size:10px">2.0</sup></h3>
		<small class="text-warning" style="font-weight:bold;text-shadow:0 1px 1px #000">Sistem Survei Kepuasan Masyarakat</small>
	</center>
				<div class="d-flex justify-content-center form_container" style="margin-top:30px">

					<form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="form-group">
                            @if (session()->has('error'))
                            <div class="alert alert-dismissible alert-danger">

                              {{session()->get('error')}}
                            </div>
                            @endif
                        </div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="username" class="form-control input_user" value="" placeholder="username">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="password">
						</div>
                        <div class="form-group">
                            <img src="{{ $captcha }}" alt="" style="width:30%;height: 40px;"> <input  type="text" name="captcha" placeholder="Masukkan Kode..." required  style="border:none;float:right;height: 40px;width:67%">
                          </div>
							<div class="justify-content-center mt-3 ">
				 	<button name="button" class="btn login_btn w-100" style="width:100%">Login</button><br><br>
				 	<center><small style="color:#f7f7f7;text-shadow:0 1px 2px #000">Bagian Organisasi Sekretariat Daerah Kabupaten Bengkalis</small>
					<br><a href="{{url('/')}}" class="text-white">Kembali ke Halaman Utama</a></center>
				   </div>
					</form>
				</div>


			</div>
		</div>
	</div>
</body>
</html>
