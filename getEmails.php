<?php
/**
 * Profile query.
 * php version 8.1.10
 *
 * @category Config
 * @package  Server
 * @author   Author <alfonsoj.gonzalez@alfonsogonz.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  GIT: @1.0.0
 * @link     https://github.com/alfonso8969/api-angular-php.git
 */

require_once "./classes/class.Database.php";
require_once "./classes/utils.php";

/* gmail connection,with port number 993 */
$host = '{imap.gmail.com:993/imap/ssl/novalidate-cert/norsh}INBOX';
/* Your gmail credentials */
$user = 'alfonso8969@gmail.com';
$password = 'jnnwczvhqxnkhjes';

$emails = array();

/* Establish a IMAP connection */
$conn = imap_open($host, $user, $password);
if (!$conn) {
    die('unable to connect Gmail: ' . imap_last_error());
}

/* Search emails from gmail inbox*/
$mails = imap_search($conn, 'FROM "noreply@jotform.com"');

/* loop through each email id mails are available. */
if ($mails) {

    /* rsort is used to display the latest emails on top */
    rsort($mails);

    /* For each email */
    foreach ($mails as $email_number) {

        $email = new Email();
        /* Retrieve specific email information*/
        $headers = imap_fetch_overview($conn, $email_number, 0);

        /*  Returns a particular section of the body*/
        
        $phpDate = strtotime($headers[0]->date);
        $mysqlDate = date('Y-m-d H:i:s', $phpDate);
        
        $email->idEmail = $headers[0]->uid;
        $email->from = $headers[0]->from;
        $email->to = $headers[0]->to;
        $email->date = $mysqlDate;
        $message = imap_body($conn, $email_number, FT_PEEK);
        $email->body = substr(trim(quoted_printable_decode(mb_convert_encoding($message, 'utf-8'))), 0, 1500);
        $email->subject = quoted_printable_decode($headers[0]->subject);
        $email->unread = $headers[0]->seen;
        $email->answered = $headers[0]->answered;
        $email->deleted = $headers[0]->deleted;
        $email->label = 'Inscription';

        /* get mail structure */
        $structure = imap_fetchstructure($conn, $email_number);
        
        $attachments = array();
             /* if any attachments found... */
        if (isset($structure->parts) && count($structure->parts)) {
            for ($i = 0; $i < count($structure->parts); $i++) {
                $attachments[$i] = array(
                'is_attachment' => false,
                'filename' => '',
                'name' => '',
                'attachment' => ''
                );

                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($conn, $email_number, $i+1);

                    /* 3 = BASE64 encoding */
                    if ($structure->parts[$i]->encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif ($structure->parts[$i]->encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        /* iterate through each attachment and save it */
        foreach ($attachments as $attachment) {
            if ($attachment['is_attachment'] == 1) {
                $filename = $attachment['name'];
                if (empty($filename)) {
                    $filename = $attachment['filename'];
                }

                if (empty($filename)) {
                    $filename = time() . ".dat";
                }
                $folder = "attachment";
                if (!is_dir($folder)) {
                     mkdir($folder);
                }
                $fp = fopen("./". $folder ."/". $email->idEmail . "-" . $filename, "w+");
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
                array_push($email->attachments, $email->idEmail . "-" . $filename);
            }
        }

        $email = Utils::utf8Converter($email);
        array_push($emails, $email);
    }// End foreach
    
    echo json_encode($emails);
}//endif

/* imap connection is closed */
imap_close($conn);

/**
 * Email class implementation
 *
 * @category Email
 * @package  Email
 * @author   Alfoso J. González <alfonsoj.gonzalez@alfonsogonz.es>
 * @license  MIT http://github.com/alfonso8969/api-angular.php.git
 * @link     http://github.com/alfonso8969/api-angular.php.git
 */
class Email
{
    public $idEmail;
    public $from;
    public $to;
    public $date;
    public $body;
    public $subject;
    public $unread;
    public $answered;
    public $deleted;
    public $label;
    public $attachments = array();
    public $favorite = false;
}