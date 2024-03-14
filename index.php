<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Mashanghat</title>
<style>
  *{
    overflow-x:hidden;
     overflow-x:hidden;
  }
  html{
    overflow-x:hidden;
     overflow-x:hidden;
  }
  html{
   overflow:hidden;
  }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
      background:#111;
      color:#00ff00;
      overflow:hidden;
    }
    #chat-container {
        position: absolute;
        bottom: 24px;
        width: 106%;
        text-align: center;
      background:#111;
      font-family:Hack;
      font-size:18px;
       font-weight: bold;
    }
    #message-input-container {
        width: 90%;
        margin: 0 auto;
      right:14px;
      position: relative;
       background:#111;
      font-family:Hack;
      font-size:18px;
       font-weight: bold;
    }
    #message-input {
        width: calc(100% - 40px);
        padding: 10px;
      padding-right: 6px;
       background:#111;
      box-shadow:0 0 14px skyblue;
      border-radius:8px;
      color:#00ff00;
      font-family:Hack;
      font-size:18px;
       font-weight: bold;
    }
    #Btn {
        position: absolute;
        right: 11px;
        top: 0;
        bottom: 0;
        padding-right:14px;
      padding-left:14px;
        border: none;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
      border-radius:4px;
    }
    #Btn:hover {
        background-color: #0056b3;
    }
    #scroll-up {
        position: fixed;
        bottom: 140px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
    display: none;
    }
    #scroll-down {
        position: fixed;
        bottom: 100px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
      display: none;

    }
    #messages {
        width: 100%;  
        margin: 20px auto;
        padding: 14px;
        padding-left:14px;
        border-radius: 5px;
        max-height: 544px;
        overflow-y: auto;
        overflow-x: hidden;
     font-family:Hack;
      font-size:18px;
       font-weight: bold;
    }

   
</style>
</head>
<body>
<div id="messages"></div>
<div id="chat-container">
    <div id="message-input-container">
        <input type="text" id="message-input" placeholder="Type your message...">
        <button id="Btn">>></button>
    </div>
</div>
<div id="scroll-up">&#9650;</div>
<div id="scroll-down">&#9660;</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
      var scrollPosition = 0;

      // Function to load messages from the server
      function loadMessages() {
          var messagesContainer = document.getElementById('messages');
          fetch('get_messages.php')
          .then(response => response.text())
          .then(data => {
              // Preserve scroll position
              scrollPosition = messagesContainer.scrollHeight - messagesContainer.scrollTop;
              document.getElementById('messages').innerHTML = data;
              // Adjust scroll position after loading new messages
              messagesContainer.scrollTop = messagesContainer.scrollHeight - scrollPosition;
          });
      }

      // Function to send message
      function sendMessage() {
          var message = document.getElementById('message-input').value;
          if (message.trim() !== '') {
              // Fetch the stored username from localStorage
              var username = getStoredUsername();
              fetch('save_message.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/x-www-form-urlencoded'
                  },
                  // Include both message and username in the request
                  body: 'message=' + encodeURIComponent(message) + '&username=' + encodeURIComponent(username)
              })
              .then(response => response.text())
              .then(data => {
                  // Clear input field after sending message
                  document.getElementById('message-input').value = '';
                  // Load messages after sending
                  loadMessages();
              });
          }
      }

      // Function to prompt user for username
      function getUsername() {
          var username = prompt("Please enter your username:");
          if (username) {
              localStorage.setItem("username", username);
          }
      }

      // Function to get the stored username
      function getStoredUsername() {
          var storedUsername = localStorage.getItem("username");
          if (!storedUsername) {
              getUsername();
              storedUsername = localStorage.getItem("username");
          }
          return storedUsername;
      }

      // Function to format the message with username prefix
      function formatMessage(message) {
          var username = getStoredUsername();
          return username + ': ' + message;
      }

      // Event listener for Send button click
      document.getElementById('Btn').addEventListener('click', sendMessage);

      // Event listener for Enter key to send message
      document.getElementById('message-input').addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
              sendMessage()
          }
      });

      // Event listener for scroll-up button
      document.getElementById('scroll-up').addEventListener('click', function() {
          document.getElementById('messages').scrollTop -= 1;
      });

      // Event listener for scroll-down button
      document.getElementById('scroll-down').addEventListener('click', function() {
          document.getElementById('messages').scrollTop += 1;
      });

      // Function to periodically load messages
      setInterval(loadMessages, 444);

      // Initial load of messages
      loadMessages();

      // Clear stored username when webpage is loaded in a new tab or reloaded
      window.addEventListener('beforeunload', function() {
          localStorage.removeItem("username");
      });
  });

</script>
</body>
</html>
