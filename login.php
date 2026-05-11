<?php

session_start();

include "includes/db.php";

$message = "";

if(isset($_POST['login'])){

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){

        $message = "<div class='alert alert-error'>
                        All fields are required
                    </div>";

    }else{

        $query = mysqli_prepare($conn,
        "SELECT id,username,password
         FROM users
         WHERE email=?");

        mysqli_stmt_bind_param($query,"s",$email);

        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        if(mysqli_num_rows($result) > 0){

            $user = mysqli_fetch_assoc($result);

            if(password_verify(
                $password,
                $user['password']
            )){

                $_SESSION['user_id']
                = $user['id'];

                $_SESSION['username']
                = $user['username'];

                header("Location: dashboard.php");
                exit();

            }else{

                $message = "<div class='alert alert-error'>
                                Invalid Password
                            </div>";
            }

        }else{

            $message = "<div class='alert alert-error'>
                            User not found
                        </div>";
        }
    }
}

include "includes/header.php";
?>

<div class="form-container">

    <h2>Welcome Back 👋</h2>

    <?php echo $message; ?>

    <form method="POST">

        <div class="input-group">
            <label>Email</label>

            <input
            type="email"
            name="email"
            placeholder="Enter email"
            required>
        </div>

        <div class="input-group">
            <label>Password</label>

            <input
            type="password"
            name="password"
            placeholder="Enter password"
            required>
        </div>

        <button
        type="submit"
        name="login"
        class="btn btn-primary"
        style="width:100%;">

            Login

        </button>

    </form>

    <br>

    <p style="text-align:center;">
        Don't have an account?

        <a href="register.php"
        style="color:#38bdf8;">

            Register

        </a>
    </p>

</div>

<?php include "includes/footer.php"; ?>