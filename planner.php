<?php

include "includes/auth.php";
include "includes/db.php";
include "api/openai.php";

$message = "";
$ai_response = "";

if(isset($_POST['save_trip'])){

    $user_id = $_SESSION['user_id'];

    $title = trim($_POST['title']);
    $budget = trim($_POST['budget']);
    $days = trim($_POST['days']);
    $destinations = trim($_POST['destinations']);
    $travel_style = trim($_POST['travel_style']);

    $ai_response = generateTripPlan(
        $budget,
        $days,
        $destinations,
        $travel_style
    );

    if(
        empty($title) ||
        empty($budget) ||
        empty($days) ||
        empty($destinations) ||
        empty($travel_style)
    ){

        $message = "
        <div class='alert alert-error'>
            All fields are required
        </div>";

    }else{

        $query = mysqli_prepare(
            $conn,

            "INSERT INTO trips
            (user_id,title,budget,days,destinations,travel_style,ai_response)

            VALUES(?,?,?,?,?,?,?)"
        );

        mysqli_stmt_bind_param(
            $query,
            "isdisss",
            $user_id,
            $title,
            $budget,
            $days,
            $destinations,
            $travel_style,
            $ai_response
        );

        if(mysqli_stmt_execute($query)){

            $message = "
            <div class='alert alert-success'>
                Trip Saved Successfully 🚀
            </div>";

        }else{

            $message = "
            <div class='alert alert-error'>
                Failed to save trip
            </div>";
        }
    }
}

include "includes/header.php";
?>

<h1 style="margin-bottom:15px;">
    🤖 AI Trip Planner
</h1>

<p style="color:#94a3b8; margin-bottom:40px;">
    Create smart travel plans powered by AI.
</p>

<?php echo $message; ?>

<div class="form-container" style="max-width:700px;">

    <form method="POST">

        <div class="input-group">
            <label>Trip Title</label>

            <input
            type="text"
            name="title"
            placeholder="Example: Ella Adventure"
            required>
        </div>

        <div class="input-group">
            <label>Budget (LKR)</label>

            <input
            type="number"
            name="budget"
            placeholder="Enter your budget"
            required>
        </div>

        <div class="input-group">
            <label>Travel Days</label>

            <input
            type="number"
            name="days"
            placeholder="Number of days"
            required>
        </div>

        <div class="input-group">
            <label>Destinations</label>

            <textarea
            name="destinations"
            placeholder="Ella, Kandy, Sigiriya..."
            required></textarea>
        </div>

        <div class="input-group">
            <label>Travel Style</label>

            <select name="travel_style" required>

                <option value="">
                    Select travel style
                </option>

                <option value="Luxury">
                    Luxury
                </option>

                <option value="Budget">
                    Budget
                </option>

                <option value="Adventure">
                    Adventure
                </option>

                <option value="Family">
                    Family
                </option>

                <option value="Solo">
                    Solo
                </option>

            </select>
        </div>

        <button
        type="submit"
        name="save_trip"
        class="btn btn-primary"
        style="width:100%;">

            Save Trip

        </button>

    </form>

</div>

<?php if(!empty($ai_response)): ?>

<div class="card"
style="margin-top:40px;">

    <h2 style="margin-bottom:20px;">
        🤖 AI Generated Travel Plan
    </h2>

    <div style="
    white-space:pre-wrap;
    line-height:1.8;
    color:#cbd5e1;
    ">

        <?php echo htmlspecialchars($ai_response); ?>

    </div>

</div>

<?php endif; ?>
<?php include "includes/footer.php"; ?>