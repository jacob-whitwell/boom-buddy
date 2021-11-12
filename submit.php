<?php
function post_captcha($user_response) {
    $fields_string = '';
    $fields = array(
        'secret' => '6LctET0UAAAAAADKPXnfvAVVoKHT9MBzPI9AqSpK',
        'response' => $user_response
    );
    foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
    $fields_string = rtrim($fields_string, '&');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

// Call the function post_captcha
$res = post_captcha($_POST['g-recaptcha-response']);

if (!$res['success']) {
    // What happens when the CAPTCHA wasn't checked
    echo '<p>Please go back and make sure you check the security CAPTCHA box.</p><br>';
} else {
    // If CAPTCHA is successfully completed...

    $post = $_POST;

//EMAIL VALIDATION
    if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        //Error
        exit;
    }

    if ($post['message'] == null) {
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
    $headers[] = 'Bcc: jacobwhitwell@gmail.com';
    $headers[] = 'Reply-To: ' . $post['email'];
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    $to = 'Keith Hicks <keith@fleetwoodfilms.com>';
    $subject = 'Contact Via Website';

// Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
    header("Location: index.php");
    exit;

}


