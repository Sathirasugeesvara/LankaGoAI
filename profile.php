<?php

include "includes/auth.php";
include "includes/db.php";

$user_id = $_SESSION['user_id'];

$message = "";

/* =========================
   GET USER
========================= */

$getUser = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id=?"
);

mysqli_stmt_bind_param(
    $getUser,
    "i",
    $user_id
);

mysqli_stmt_execute($getUser);

$userResult = mysqli_stmt_get_result($getUser);

$user = mysqli_fetch_assoc($userResult);

/* =========================
   UPDATE PROFILE
========================= */

if(isset($_POST['update_profile'])){

    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $nationality = trim($_POST['nationality']);
    $passport_number = trim($_POST['passport_number']);
    $bio = trim($_POST['bio']);

    /* PROFILE IMAGE */

    $profile_image = $user['profile_image'];

    if(
        isset($_FILES['profile_image']) &&
        $_FILES['profile_image']['name'] != ""
    ){

        $imageName =
        time() . "_" .
        basename($_FILES['profile_image']['name']);

        $target =
        "uploads/profile_images/" . $imageName;

        move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            $target
        );

        $profile_image = $imageName;
    }

    /* UPDATE USER */

    $update = mysqli_prepare(

        $conn,

        "UPDATE users

        SET
        full_name=?,
        username=?,
        email=?,
        phone=?,
        gender=?,
        dob=?,
        nationality=?,
        passport_number=?,
        bio=?,
        profile_image=?

        WHERE id=?"
    );

    mysqli_stmt_bind_param(

        $update,

        "ssssssssssi",

        $full_name,
        $username,
        $email,
        $phone,
        $gender,
        $dob,
        $nationality,
        $passport_number,
        $bio,
        $profile_image,
        $user_id
    );

    if(mysqli_stmt_execute($update)){

        $_SESSION['username'] = $username;

        $message = "
        <div class='alert alert-success'>
            Profile Updated Successfully 🚀
        </div>";

        // REFRESH USER

        $getUser = mysqli_prepare(
            $conn,
            "SELECT * FROM users WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $getUser,
            "i",
            $user_id
        );

        mysqli_stmt_execute($getUser);

        $userResult = mysqli_stmt_get_result($getUser);

        $user = mysqli_fetch_assoc($userResult);

    }else{

        $message = "
        <div class='alert alert-error'>
            Failed to update profile
        </div>";
    }
}

/* =========================
   CHANGE PASSWORD
========================= */

if(isset($_POST['change_password'])){

    $current_password =
    trim($_POST['current_password']);

    $new_password =
    trim($_POST['new_password']);

    $confirm_password =
    trim($_POST['confirm_password']);

    if(
        empty($current_password) ||
        empty($new_password) ||
        empty($confirm_password)
    ){

        $message = "
        <div class='alert alert-error'>
            All password fields are required
        </div>";

    }elseif($new_password != $confirm_password){

        $message = "
        <div class='alert alert-error'>
            Passwords do not match
        </div>";

    }elseif(!password_verify(
        $current_password,
        $user['password']
    )){

        $message = "
        <div class='alert alert-error'>
            Current password is incorrect
        </div>";

    }else{

        $hashedPassword =
        password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );

        $passwordQuery = mysqli_prepare(
            $conn,
            "UPDATE users SET password=? WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $passwordQuery,
            "si",
            $hashedPassword,
            $user_id
        );

        if(mysqli_stmt_execute($passwordQuery)){

            $message = "
            <div class='alert alert-success'>
                Password Updated Successfully 🔐
            </div>";

        }else{

            $message = "
            <div class='alert alert-error'>
                Failed to update password
            </div>";
        }
    }
}

include "includes/header.php";
?>

<h1 style="margin-bottom:15px;">
    👤 My Profile
</h1>

<p style="color:#94a3b8; margin-bottom:40px;">
    Manage your personal and travel information.
</p>

<?php echo $message; ?>

<div class="dashboard-grid">

    <!-- PROFILE CARD -->

    <div class="card" style="text-align:center;">

        <img

        src="uploads/profile_images/<?php
        echo $user['profile_image']; ?>"

        alt="Profile"

        style="
        width:140px;
        height:140px;
        border-radius:50%;
        object-fit:cover;
        margin-bottom:20px;
        border:4px solid #38bdf8;
        ">

        <h2>
            <?php
            echo htmlspecialchars(
                $user['full_name']
            );
            ?>
        </h2>

        <p style="color:#94a3b8;">

            @<?php
            echo htmlspecialchars(
                $user['username']
            );
            ?>

        </p>

        <br>

        <p>

            🌍

            <?php
            echo htmlspecialchars(
                $user['nationality']
            );
            ?>

        </p>

        <br><br>

        <button
        type="button"
        class="btn btn-primary"
        onclick="openPasswordModal()">

            🔐 Change Password

        </button>

    </div>

    <!-- PROFILE FORM -->

    <div class="form-container"
    style="max-width:100%;">

        <form
        method="POST"
        enctype="multipart/form-data">

            <div class="input-group">

                <label>Profile Image</label>

                <input
                type="file"
                name="profile_image">

            </div>

            <div class="input-group">

                <label>Full Name</label>

                <input
                type="text"
                name="full_name"

                value="<?php
                echo htmlspecialchars(
                    $user['full_name']
                );
                ?>"

                required>

            </div>

            <div class="input-group">

                <label>Username</label>

                <input
                type="text"
                name="username"

                value="<?php
                echo htmlspecialchars(
                    $user['username']
                );
                ?>"

                required>

            </div>

            <div class="input-group">

                <label>Email</label>

                <input
                type="email"
                name="email"

                value="<?php
                echo htmlspecialchars(
                    $user['email']
                );
                ?>"

                required>

            </div>

            <div class="input-group">

                <label>Phone Number</label>

                <input
                type="text"
                name="phone"

                value="<?php
                echo htmlspecialchars(
                    $user['phone']
                );
                ?>">

            </div>

            <div class="input-group">

                <label>Gender</label>

                <select name="gender">

                    <option value="">
                        Select Gender
                    </option>

                    <option value="Male"
                    <?php
                    if($user['gender']=="Male")
                    echo "selected";
                    ?>>
                        Male
                    </option>

                    <option value="Female"
                    <?php
                    if($user['gender']=="Female")
                    echo "selected";
                    ?>>
                        Female
                    </option>

                    <option value="Other"
                    <?php
                    if($user['gender']=="Other")
                    echo "selected";
                    ?>>
                        Other
                    </option>

                </select>

            </div>

            <div class="input-group">

                <label>Date of Birth</label>

                <input
                type="date"
                name="dob"

                value="<?php
                echo $user['dob'];
                ?>">

            </div>

            <div class="input-group">

                <label>Nationality</label>

                <input
                type="text"
                name="nationality"

                value="<?php
                echo htmlspecialchars(
                    $user['nationality']
                );
                ?>">

            </div>

            <div class="input-group">

                <label>Passport Number</label>

                <input
                type="text"
                name="passport_number"

                value="<?php
                echo htmlspecialchars(
                    $user['passport_number']
                );
                ?>">

            </div>

            <div class="input-group">

                <label>Bio</label>

                <textarea
                name="bio"><?php

                echo htmlspecialchars(
                    $user['bio']
                );

                ?></textarea>

            </div>

            <button
            type="submit"
            name="update_profile"
            class="btn btn-primary"
            style="width:100%;">

                Update Profile

            </button>

        </form>

    </div>

</div>

<!-- PASSWORD MODAL -->

<div
id="passwordModal"

style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.7);
backdrop-filter:blur(8px);
justify-content:center;
align-items:center;
z-index:9999;
">

    <div class="form-container"

    style="
    max-width:500px;
    position:relative;
    border:1px solid rgba(255,255,255,0.08);
    box-shadow:0 10px 40px rgba(0,0,0,0.4);
    animation:popup 0.3s ease;
    ">

        <button

        type="button"

        onclick="closePasswordModal()"

        style="
        position:absolute;
        top:15px;
        right:15px;
        background:none;
        border:none;
        color:white;
        font-size:20px;
        cursor:pointer;
        ">

            ✖

        </button>

        <h2 style="margin-bottom:25px;">
            🔐 Change Password
        </h2>

        <form method="POST">

            <div class="input-group">

                <label>Current Password</label>

                <input
                type="password"
                name="current_password"
                required>

            </div>

            <div class="input-group">

                <label>New Password</label>

                <input
                type="password"
                name="new_password"
                required>

            </div>

            <div class="input-group">

                <label>Confirm New Password</label>

                <input
                type="password"
                name="confirm_password"
                required>

            </div>

            <button
            type="submit"
            name="change_password"
            class="btn btn-primary"
            style="width:100%;">

                Update Password

            </button>

        </form>

    </div>

</div>

<script>

function openPasswordModal(){

    document.getElementById(
        "passwordModal"
    ).style.display = "flex";
}

function closePasswordModal(){

    document.getElementById(
        "passwordModal"
    ).style.display = "none";
}

window.onclick = function(event){

    const modal =
    document.getElementById("passwordModal");

    if(event.target == modal){

        modal.style.display = "none";
    }
}

</script>

<?php include "includes/footer.php"; ?>