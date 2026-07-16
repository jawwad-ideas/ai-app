<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Base</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10 bg-white rounded-lg shadow-lg p-6">

    <h1 class="text-3xl font-bold mb-6">
        📄 Upload Knowledge Document
    </h1>

    <input
        id="document"
        type="file"
        accept=".pdf"
        class="w-full border rounded-lg p-3 mb-4">

    <button
        id="upload"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
        Upload PDF
    </button>

    <div
        id="response"
        class="bg-gray-100 rounded-lg p-4 mt-6 min-h-[120px] whitespace-pre-wrap">
        Waiting for upload...
    </div>

</div>

<script>

const uploadButton = document.getElementById('upload');
const responseBox = document.getElementById('response');

uploadButton.addEventListener('click', uploadDocument);

async function uploadDocument() {

    const file = document.getElementById('document').files[0];

    if (!file) {
        responseBox.innerHTML = "❌ Please select a PDF file.";
        return;
    }

    const formData = new FormData();
    formData.append('document', file);

    responseBox.innerHTML = "📤 Uploading...";

    try {

        const response = await fetch('/documents', {

            method: 'POST',

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },

            body: formData

        });

        if (!response.ok) {
            throw new Error("Upload failed.");
        }

        const data = await response.json();

        responseBox.textContent =
            JSON.stringify(data, null, 4);

    } catch (error) {

        responseBox.innerHTML = "❌ " + error.message;

    }

}

</script>

</body>

</html>