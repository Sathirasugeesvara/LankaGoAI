function sendMessage(){

    const input =
    document.getElementById(
        "user-input"
    );

    const chatBox =
    document.getElementById(
        "chat-box"
    );

    const userText =
    input.value.trim();

    if(userText === "") return;

    /* USER MESSAGE */

    chatBox.innerHTML += `

    <div class="message user">

        <div class="bubble">

            ${userText}

        </div>

    </div>

    `;

    /* CLEAR INPUT */

    input.value = "";

    /* AUTO SCROLL */

    chatBox.scrollTop =
    chatBox.scrollHeight;

    /* TYPING */

    chatBox.innerHTML += `

    <div
    class="message ai"
    id="typing">

        <div class="bubble">

            Typing...

        </div>

    </div>

    `;

    chatBox.scrollTop =
    chatBox.scrollHeight;

    /* AI REPLY */

    setTimeout(()=>{

        document
        .getElementById("typing")
        .remove();

        let reply =
        generateReply(userText);

        chatBox.innerHTML += `

        <div class="message ai">

            <div class="bubble">

                ${reply}

            </div>

        </div>

        `;

        chatBox.scrollTop =
        chatBox.scrollHeight;

    },1200);
}

/* SMART REPLIES */

function generateReply(message){

    let text =
    message.toLowerCase();

    /* ELLA */

    if(text.includes("ella")){

        return `
        🌿 Ella is one of the best
        mountain destinations in
        Sri Lanka.<br><br>

        ⭐ Nine Arches Bridge<br>
        ⭐ Little Adam’s Peak<br>
        ⭐ Ravana Falls<br>
        ⭐ Train Ride Experience
        `;
    }

    /* KANDY */

    else if(text.includes("kandy")){

        return `
        🏯 Kandy is famous for:

        <br><br>

        ⭐ Temple of the Tooth<br>
        ⭐ Kandy Lake<br>
        ⭐ Botanical Garden<br>
        ⭐ Cultural Dance Shows
        `;
    }

    /* BEACH */

    else if(
        text.includes("beach")
    ){

        return `
        🏖️ Best beaches in
        Sri Lanka:<br><br>

        ⭐ Mirissa<br>
        ⭐ Unawatuna<br>
        ⭐ Bentota<br>
        ⭐ Arugam Bay
        `;
    }

    /* FOOD */

    else if(
        text.includes("food")
    ){

        return `
        🍛 Must-try Sri Lankan food:

        <br><br>

        ⭐ Kottu<br>
        ⭐ Hoppers<br>
        ⭐ Rice & Curry<br>
        ⭐ String Hoppers
        `;
    }

    /* WEATHER */

    else if(
        text.includes("weather")
    ){

        return `
        🌦️ Sri Lanka weather
        is usually tropical and warm.

        <br><br>

        ☀️ Coastal areas are hot.
        🌿 Hill country is cooler.
        ☔ Carry umbrellas during
        rainy seasons.
        `;
    }

    /* HOTEL */

    else if(
        text.includes("hotel")
    ){

        return `
        🏨 Popular hotel areas:

        <br><br>

        ⭐ Colombo 03<br>
        ⭐ Kandy City<br>
        ⭐ Ella Town<br>
        ⭐ Mirissa Beach
        `;
    }

    /* TRANSPORT */

    else if(
        text.includes("transport")
    ){

        return `
        🚆 Sri Lanka transport tips:

        <br><br>

        ⭐ Use PickMe app<br>
        ⭐ Train rides are scenic<br>
        ⭐ Tuk-Tuks for short trips<br>
        ⭐ Buses are budget friendly
        `;
    }

    /* DEFAULT */

    else{

        const replies = [

            `
            ✈️ I can help you with:
            <br><br>

            ⭐ Places to visit<br>
            ⭐ Hotels<br>
            ⭐ Weather<br>
            ⭐ Budget travel<br>
            ⭐ Food recommendations
            `,

            `
            🌍 Sri Lanka has amazing
            destinations like Ella,
            Kandy, Sigiriya,
            Mirissa, and Nuwara Eliya.
            `,

            `
            🤖 Try asking me about:
            <br><br>

            ⭐ Beaches<br>
            ⭐ Mountains<br>
            ⭐ Travel costs<br>
            ⭐ Local food<br>
            ⭐ Tourist attractions
            `
        ];

        return replies[
            Math.floor(
                Math.random()
                * replies.length
            )
        ];
    }
}