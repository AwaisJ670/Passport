<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Merit Chatbot</title>
    <!-- Include Bootstrap for basic styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .chat-box {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .chat-log {
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #e9ecef;
        }
        .chat-log .user-message, .chat-log .bot-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 15px;
        }
        .user-message {
            background-color: #007bff;
            color: white;
            text-align: right;
        }
        .bot-message {
            background-color: #6c757d;
            color: white;
            text-align: left;
        }
        .message-input {
            display: flex;
        }
        .message-input input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .message-input button {
            margin-left: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="chat-box">
        <h4 class="text-center">Merit Status Chatbot</h4>
        <div id="chat-log" class="chat-log">
            <!-- Chat log will be displayed here -->
        </div>
        <div class="message-input">
            <input type="text" id="user-input" placeholder="Type your message here...">
            <button id="send-btn">Send</button>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#send-btn').on('click', function() {
            sendMessage();
        });

        $('#user-input').on('keypress', function(e) {
            if (e.which === 13) { // Enter key pressed
                sendMessage();
            }
        });

        function sendMessage() {
            let userInput = $('#user-input').val().trim();

            if (userInput === '') {
                return; // Prevent empty input
            }

            // Append the user message to the chat log
            $('#chat-log').append('<div class="user-message">' + userInput + '</div>');
            $('#user-input').val(''); // Clear the input field
            scrollChatLog(); // Scroll to the bottom

            // Send the user input to the server (Dialogflow)
            $.ajax({
                url: "{{ route('detect.intent') }}",
                method: 'POST',
                data: {
                    query: userInput,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Append the bot's response to the chat log
                    $('#chat-log').append('<div class="bot-message">' + response.response + '</div>');
                    scrollChatLog(); // Scroll to the bottom
                },
                error: function() {
                    $('#chat-log').append('<div class="bot-message">There was an error processing your request.</div>');
                    scrollChatLog(); // Scroll to the bottom
                }
            });
        }

        function scrollChatLog() {
            $('#chat-log').scrollTop($('#chat-log')[0].scrollHeight);
        }
    });
</script>

</body>
</html>
