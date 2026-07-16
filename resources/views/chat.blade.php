<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Gemini Chatbot</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center p-8">

    <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-6">

        <h1 class="text-3xl font-bold text-center mb-6">
            🤖 Laravel Gemini Chatbot
        </h1>

        <textarea
            id="message"
            rows="5"
            class="w-full border rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Ask anything..."
        ></textarea>

        <button
            id="send"
            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg"
        >
            Send
        </button>

        <div class="mt-8">

            <h2 class="font-bold text-xl mb-3">
                Gemini Response
            </h2>

            <div
                id="response"
                class="bg-gray-100 rounded-lg p-4 min-h-[150px] whitespace-pre-wrap"
            >
                Waiting for your question...
            </div>

        </div>

    </div>

</div>

<script>
const messageInput = document.getElementById('message');
const responseBox = document.getElementById('response');
const sendButton = document.getElementById('send');

// Click to send
sendButton.addEventListener('click', sendMessage);

// Press Enter to send
messageInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

async function sendMessage() {

    const message = messageInput.value.trim();

    if (!message) {
        return;
    }

    responseBox.innerHTML = "🤔 Thinking...";

    try {

        const response = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message
            })
        });

        if (!response.ok) {
            throw new Error('Something went wrong.');
        }

        const data = await response.json();

        // Show only the message
        responseBox.innerHTML = data.message.message;

        // Clear textbox
        messageInput.value = '';
        messageInput.focus();

        console.log(data);

    } catch (error) {

        responseBox.innerHTML = "❌ " + error.message;

    }
}
</script>

</body>
</html>