<?php

include "includes/auth.php";
include "includes/header.php";

?>

<h1 style="margin-bottom:15px;">
    🤖 AI Travel Assistant
</h1>

<p style="
color:#94a3b8;
margin-bottom:30px;
">

    Ask travel questions about Sri Lanka.

</p>

<div class="chat-container">

    <div
    id="chat-box"
    class="chat-box">

        <div class="bot-message">

            👋 Hello! I am LankaGoAI Assistant.
            Ask me about places, travel tips,
            hotels, food, or weather.

        </div>

    </div>

    <div class="chat-input-area">

        <input
        type="text"

        id="user-input"

        placeholder="Ask something...">

        <button onclick="sendMessage()">

            Send

        </button>

    </div>

</div>

<script src="assets/js/chatbot.js"></script>

<?php include "includes/footer.php"; ?>