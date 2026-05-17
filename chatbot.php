<?php

include "includes/auth.php";
include "includes/header.php";
include "includes/Parsedown.php";

$parsedown = new Parsedown();

?>

<div class="chat-page">

    <!-- HEADER -->

    <div class="chat-header">

        <div>

            <h1>
                🤖 AI Travel Assistant
            </h1>

            <p>
                Ask anything about Sri Lanka travel.
            </p>

        </div>

        <div class="online-status">

            <span class="dot"></span>

            AI Online

        </div>

    </div>

    <!-- CHAT CONTAINER -->

    <div class="chat-container">

        <!-- CHAT BOX -->

        <div
        id="chat-box"
        class="chat-box">

            <!-- AI MESSAGE -->

            <div class="message ai">

                <div class="bubble">

                    👋 Hello! I am LankaGoAI Assistant.<br><br>

                </div>

            </div>

        </div>

        <!-- INPUT -->

        <div class="chat-input">

            <input
            type="text"

            id="user-input"

            placeholder="Ask something about Sri Lanka travel..."

            onkeypress="
            if(event.key==='Enter'){
                sendMessage();
            }
            ">

            <button onclick="sendMessage()">

                <i class="fa-solid fa-paper-plane"></i>

            </button>

        </div>

    </div>

</div>

<script src="assets/js/chatbot.js"></script>

<?php include "includes/footer.php"; ?>
