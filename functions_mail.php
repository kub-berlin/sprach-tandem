<?php

require_once 'Mail.php';

/* ===================================
#
#   E-Mail
#
   ==================================*/
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) &&
        preg_match('/@.+\./', $email) &&
        !(
            strpos($email, ",") > 0 or
            strpos($email, ";") > 0 or
            strpos($email, "\n") > 0
        ) &&
        !in_array($email, $GLOBALS['email_blocklist']);
}


function sendEmail($to, $subject, $body, $replyto, $debug = 0)
{
    $ret = 0;
    $from = 'noreply@'.$GLOBALS['domain'];
    $headers = array(
        "From: {$GLOBALS["organisationName"]} <$from>",
        "Reply-To: $replyto",
        "MIME-Version: 1.0",
        "Content-type: text/plain; charset=UTF-8");
    if ($debug) {
        array_push($headers, "BCC: {$GLOBALS['email_orga']}");
    }
    $mail = mail($to, $subject, $body, implode("\n", $headers));
    if (PEAR::isError($mail)) {
        if ($GLOBALS['debug'] == 1) {
            echo "<p>in function sendEmail: ".$mail->getMessage() ."</p>";
        }
        writeLog('EMAIL SENDEMAIL: '.$to.': '.$subject.': \nERROR MESSAGE: '.$mail->getMessage());
        $ret = -1;
    } else {
        $ret = 1;
    }
    return $ret;

}


function send_notification_add($to, $name, $id, $hash, $label)
{
    $subject = sprintf($label["Add_email_subject"], $GLOBALS["organisationName"]);
    $baseLink = $GLOBALS['tandem_root_path'] . "tid=$id&lang={$label['lang']}&a=$hash";
    $body = sprintf(
        $label["Add_notification_email"],
        $name,
        $GLOBALS["organisationName"],
        $baseLink . '&action=release',
        $GLOBALS["organisationName"],
        $GLOBALS["organisationName"],
        $baseLink . '&action=delete',
        $baseLink . '&action=edit',
        $GLOBALS['email_orga']
    );

    $gesendet = sendEmail($to, ($subject), ($body), 'noreply@'.$GLOBALS['domain']);
    writeLog('send_notification_add: Email senden: '.$gesendet);
    return $gesendet;
}

function send_notification_view($to, $nameTo, $nameFrom, $spracheAng, $spracheGes, $alter, $geschlecht, $ort, $email, $beschreibung, $label)
{
    $subject = sprintf($label["View_email_subject"], $GLOBALS["organisationName"]);
    $body = sprintf(
        $label['View_notification_email'],
        $nameTo,
        $nameFrom,
        $label[$spracheAng],
        $label[$spracheGes],
        $alter,
        $geschlecht,
        $ort,
        $email,
        $beschreibung,
        $GLOBALS["organisationName"]
    );

    $gesendet = sendEmail($to, ($subject), ($body), $email, true);
    writeLog('send_notification_view: Email senden: '.$gesendet);
    return $gesendet;
}

function send_notification_report($to, $name, $email, $text, $name_reported, $id_reported, $text_reported, $label)
{
    $subject = sprintf($label["Report_email_subject"], $GLOBALS["organisationName"]);
    $body = sprintf(
        $label["Report_email"],
        $name,
        $email,
        $text,
        $name_reported,
        $id_reported,
        $text_reported
    );

    $gesendet = sendEmail($to, $subject, $body, $email);
    writeLog('send_notification_report: Email senden: '.$gesendet);
    return $gesendet;
}

function send_reminder($to, $name, $id, $hash, $label)
{
    $subject = sprintf($label["Reminder_subject"], $GLOBALS["organisationName"]);
    $baseLink = $GLOBALS['tandem_root_path'] . "tid=$id&lang={$label['lang']}&a=$hash";
    $body = sprintf(
        $label["Reminder_email"],
        $name,
        $GLOBALS["organisationName"],
        $GLOBALS["organisationName"],
        $baseLink . "&action=delete",
        $baseLink . "&action=edit",
        $GLOBALS['email_orga']
    );

    $gesendet = sendEmail($to, $subject, $body, 'noreply@'.$GLOBALS['domain']);
    writeLog('send_notification_add: Email senden: '.$gesendet);
    return $gesendet;
}
