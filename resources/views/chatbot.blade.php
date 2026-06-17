<!DOCTYPE html>
<html>
<head>
    <title>Asistente Procafes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<h2>Asistente Virtual Procafes</h2>

<div id="chat" style="height:400px;border:1px solid #ccc;padding:10px;overflow:auto">
</div>

<input type="text" id="message" placeholder="Escribe tu pregunta">
<button onclick="sendMessage()">Enviar</button>

<script>
function sendMessage() {

    let message = document.getElementById('message').value;

    fetch('/chatbot/send', {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: message
        })
    })
   .then(response => response.json())
.then(data => {

    console.log("RESPUESTA:", data);

    let chat = document.getElementById('chat');

    chat.innerHTML += `
        <p><b>Tú:</b> ${message}</p>
        <p><b>Bot:</b> ${JSON.stringify(data)}</p>
    `;

    document.getElementById('message').value='';
});

}
</script>

</body>
</html>