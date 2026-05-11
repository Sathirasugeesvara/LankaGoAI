<?php

include "includes/db.php";

$message = "";

if(isset($_POST['register'])){

    $username  = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);

    if(
        empty($username) ||
        empty($full_name) ||
        empty($email) ||
        empty($password)
    ){

        $message = "
        <div class='alert alert-error'>
            All fields are required
        </div>";

    }else{

        // CHECK EMAIL

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email=?"
        );

        mysqli_stmt_bind_param($check,"s",$email);

        mysqli_stmt_execute($check);

        mysqli_stmt_store_result($check);

        if(mysqli_stmt_num_rows($check) > 0){

            $message = "
            <div class='alert alert-error'>
                Email already exists
            </div>";

        }else{

            // HASH PASSWORD

            $hashedPassword =
            password_hash($password,PASSWORD_DEFAULT);

            // INSERT USER

            $query = mysqli_prepare(
                $conn,

                "INSERT INTO users
                (username,full_name,email,password)

                VALUES(?,?,?,?)"
            );

            mysqli_stmt_bind_param(
                $query,
                "ssss",
                $username,
                $full_name,
                $email,
                $hashedPassword
            );

            if(mysqli_stmt_execute($query)){

                $message = "
                <div class='alert alert-success'>
                    Registration Successful 🚀
                </div>";

            }else{

                $message = "
                <div class='alert alert-error'>
                    Something went wrong
                </div>";
            }
        }
    }
}

include "includes/header.php";
?>

<div class="form-container">

    <h2>Create Account 🚀</h2>

    <?php echo $message; ?>

    <form method="POST">

        <div class="input-group">
            <label>Username</label>

            <input
            type="text"
            name="username"
            placeholder="Enter username"
            required>
        </div>

        <div class="input-group">
            <label>Full Name</label>

            <input
            type="text"
            name="full_name"
            placeholder="Enter full name"
            required>
        </div>

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
        name="register"
        class="btn btn-primary"
        style="width:100%;">

            Register

        </button>

    </form>

    <br>

    <p style="text-align:center;">

        Already have an account?

        <a href="login.php"
        style="color:#38bdf8;">

            Login

        </a>

    </p>

</div>

<?php include "includes/footer.php"; ?>