<?php

$post = $_POST;

//EMAIL VALIDATION
if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
    //Error
    exit;
}

// message
$message = '
<html>
    <head>
    <title>Contact</title>
    </head>
        <body>
        <h1>You\'ve received a message through boom-buddy.com</h1>
        <table>
            <tr>
                <td>E-mail address: </td>
                <td>' . $post['email'] . '</td>
            </tr>
            <tr>
                <td valign="top">Message: </td>
                <td valign="top">' . nl2br($post['message']) . '</td>
            </tr>
            <tr>
                <td valign="top">Phone Number: </td>
                <td valign="top">' . nl2br($post['number']) . '</td>
            </tr>
        </table>
    </body>
</html>
';

$headers = array();
// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Additional headers
$headers[] = 'From: Boom-Buddy Contact <info@boom-buddy.com>';
$headers[] = 'Reply-To: ' . $post['email'];
$headers[] = 'X-Mailer: PHP/' . phpversion();

$to = 'Keith Hicks <keith@fleetwoodfilms.com>';
$subject = 'Contact Via Website';

// Mail it
mail($to, $subject, $message, implode("\r\n", $headers));
header("Location: index.php");
exit;