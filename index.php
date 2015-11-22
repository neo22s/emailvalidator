<?
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

//only on request we check the email
if ($_REQUEST AND isset($_REQUEST['email']))
{
    require_once 'emailvalidator.php';
    $check = emailvalidator::check($_REQUEST['email']);
    header('Content-Type: application/json');
    die(json_encode(array(  'result'  => ($check===TRUE)?TRUE:FALSE,
                            'message' => ($check===TRUE)?$_REQUEST['email']:$check)
        ));
}
?>

Email validator!
<form method="GET" >
    <input type="text" name="email">
    <input type="submit" value="check">
</form>
How to use it?

How works:

Source Code
Json with disposable emails https://github.com/ivolo/disposable-email-domains/blob/master/index.json