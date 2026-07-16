<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laravel RAG Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-6">
        Laravel RAG Chat
    </h1>

    <textarea
        id="question"
        class="w-full border rounded p-3"
        rows="5"
        placeholder="Ask something..."
    ></textarea>

    <button
        id="ask"
        class="mt-4 bg-blue-600 text-white px-5 py-2 rounded"
    >
        Ask
    </button>

    <pre
        id="response"
        class="bg-gray-200 mt-5 p-4 rounded whitespace-pre-wrap"
    ></pre>

</div>

<script>

document
.getElementById('ask')
.addEventListener('click', async () => {

    const question =
        document.getElementById('question').value;

    const response = await fetch('/search',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':
            document
            .querySelector('meta[name="csrf-token"]')
            .content
        },

        body:JSON.stringify({
            question
        })

    });

    const data = await response.text();

    document
    .getElementById('response')
    .textContent =data;

});

</script>

</body>
</html>