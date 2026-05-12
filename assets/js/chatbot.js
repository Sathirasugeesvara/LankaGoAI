const chatBox =
document.getElementById("chat-box");

function sendMessage(){

    const input =
    document.getElementById("user-input");

    const message =
    input.value.trim();

    if(message === "") return;

    // USER MESSAGE

    const userDiv =
    document.createElement("div");

    userDiv.className =
    "user-message";

    userDiv.innerText = message;

    chatBox.appendChild(userDiv);

    // BOT RESPONSE

    const botDiv =
    document.createElement("div");

    botDiv.className =
    "bot-message";

    botDiv.innerText =
    getBotReply(message);

    setTimeout(()=>{

        chatBox.appendChild(botDiv);

        chatBox.scrollTop =
        chatBox.scrollHeight;

    },700);

    input.value = "";

    chatBox.scrollTop =
    chatBox.scrollHeight;
}

function getBotReply(message){

    message = message.toLowerCase();

    if(message.includes("ella")){

        return `
Best places in Ella:

• Nine Arches Bridge
• Little Adam's Peak
• Ravana Falls
• Ella Rock
• Train ride experience
        `;
    }

    if(message.includes("kandy")){

        return `
Top attractions in Kandy:

• Temple of Tooth
• Kandy Lake
• Botanical Garden
• Cultural Dance Show
        `;
    }

    if(message.includes("food")){

        return `
Must-try Sri Lankan foods:

• Kottu
• Rice & Curry
• Hoppers
• String Hoppers
• Seafood
        `;
    }

    if(message.includes("beach")){

        return `
Best beaches in Sri Lanka:

• Mirissa
• Unawatuna
• Arugam Bay
• Bentota
        `;
    }

    return `
I can help with:

• Destinations
• Hotels
• Food
• Weather
• Travel tips
• Budget planning
    `;
}