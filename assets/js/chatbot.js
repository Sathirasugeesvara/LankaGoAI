async function sendMessage() {

    let input = document.getElementById("user-input");

    let message = input.value.trim();

    if(message === "") return;

    let chatBox = document.getElementById("chat-box");

    // USER MESSAGE

    chatBox.innerHTML += `

        <div class="message user">
            <div class="bubble">
                ${message}
            </div>
        </div>

    `;

    input.value = "";

    // LOADING

    chatBox.innerHTML += `

        <div class="message ai loading">
            <div class="bubble">
                Typing...
            </div>
        </div>

    `;

    chatBox.scrollTop = chatBox.scrollHeight;

    try{

        let response = await fetch("api/chat-api.php",{

            method: "POST",

            headers:{
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
            "message=" + encodeURIComponent(message)
        });

        let data = await response.json();

        // REMOVE LOADING

        document.querySelector(".loading").remove();

        // AI MESSAGE

        chatBox.innerHTML += `

            <div class="message ai">
                <div class="bubble">
                    ${data.reply}
                </div>
            </div>

        `;

        chatBox.scrollTop = chatBox.scrollHeight;

    }catch(error){

        document.querySelector(".loading").remove();

        chatBox.innerHTML += `

            <div class="message ai">
                <div class="bubble">
                    Error connecting AI.
                </div>
            </div>

        `;
    }
}