<?php
require('connection.php');
session_start();

$error = '';
$validate = '';

//cek form registrasi submit atau tidak
if(isset($_POST['submit'])){
    
    $username = stripslashes($_POST['username']); //menghilangkan backslash
    $username =mysqli_real_escape_string($con,$username); //cara mengamankan dari sql injection

    $name = stripslashes($_POST['name']);
    $name = mysqli_real_escape_string($con,$name);

    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($con,$email);

    $pass = stripslashes($_POST['password']);
    $pass = mysqli_real_escape_string($con,$pass);

    $repass   = stripslashes($_POST['repassword']);
    $repass   = mysqli_real_escape_string($con, $repass);

    //cek input kosong atau tidak
    if (!empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($pass)) && !empty(trim($repass)) ) {
        //cek password sama atau tidak
        if ($pass == $repass) {
            //mengecek nama user sudah/belum terdaftar di database
            if (cek_nama($name,$con) == 0) {
                //hashing password sebelum disimpan ke database
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                //insert ke database
                $query = "INSERT INTO users (username,name,email,password) VALUES ('$username','$name','$email','$pass')";
                $result =   mysqli_query($con,$query);
                //jika insert data berhasil maka redirect ke index
                if ($result) {
                    $_SESSION['username'] = $username;
                    header('location: index.php');
                }else{
                    $error = 'Register Gagal';
                }
            }else{
                $error ="Username sudah terdaftar";
            }
            $validate ='password tidak sama';
        }else{
            $error = 'Data tidak boleh kosong';
        }
    }
}

    //fungsi cek username sudah terdaftar apa belum
    function cek_nama($username,$con){
        $nama = mysqli_real_escape_string($con,$username);
        $query ="SELECT * FROM users WHERE username ='$username'";
        if ($result = mysqli_query($con,$query)) {
            return mysqli_num_rows($result);
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        
        <!-- costum css -->
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <section class="container-fluid mb-4">
            <section class="row justify-content-center">
                <section class="col-12 col-sm-6 col-sm-4">
                    <form action="register.php" method="POST" class="container">
                        <h4 class="text-center font-weight-bold">Sign Up</h4>
                        <?php if ($error != '') { ?>
                            <div class="alert alert-danger" role="alert"> <?= $error ?> </div> 
                        <?php } ?>

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama">
                        </div>

                        <div class="form-group">
                            <label for="inputEmail">Alamat Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" aria-describeby="emailHelp" placeholder="Masukan Email">  
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder ="Masukan Username">
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Masukan Password">
                            <?php  if($validate != '') {?>
                                <p class="text-danger"> <?= $validate; ?> </p>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">Re-Password</label>
                            <input type="password" class="form-control" id="inputPassword" name="repassword" placeholder="Masukan RE-Password">
                            <?php  if ($validate != '') {?>
                                <p class="text-danger"> <?= $validate; ?> </p>
                            <?php } ?>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>

                        <div class="form-footer mt-2">
                            <p>sudah punya akun? <a href="login.php">Login</a></p>
                        </div>
                    </form>
                </section>
            </section>
        </section>
        <!-- Bootstrap requirement jQuery pada posisi pertama, kemudian Popper.js, dan  yang terakhit Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
    </html>

