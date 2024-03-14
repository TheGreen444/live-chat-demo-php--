<?php
$message = $_POST['message'] ?? '';
$username = $_POST['username'] ?? 'Anonymous'; // Default to 'Anonymous' if no username is provided

if (!empty($message)) {
    // Format the message with the username prefix
    $formattedMessage = 'root@' . $username . ' ~# ' . $message . PHP_EOL;

    // Open the database file in append mode
    $file = fopen('database.txt', 'a');

    // Append the formatted message to the file
    fwrite($file, $formattedMessage);

    // Close the file
    fclose($file);
}
?>
