<?
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

//only on request we check the email
if ($_REQUEST AND isset($_REQUEST['email']))
{
    require_once 'emailvalidator.php';
    $check = emailvalidator::check($_REQUEST['email']);
    header('Content-Type: application/json');
    die(json_encode(array(  'valid'  => ($check===TRUE)?TRUE:FALSE,
                            'message' => ($check===TRUE)?$_REQUEST['email']:$check)
        ));
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Email Validator is a free JSON API that gives you a simple way to validate/verify email addresses. You can get the free source code and run it on your server or use this service for free ;)">
    <meta name="author" content="garridodiaz.com">

    <title>Email Validator, Free API, Verify email addresses.</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">

   
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Email Validator!</h3>
              <nav>
                <ul class="nav masthead-nav">
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="https://github.com/neo22s/emailvalidator/issues">Github</a></li>
                    <li><a href="https://github.com/neo22s/emailvalidator/">Issues</a></li>
                    <li><a href="https://github.com/neo22s/emailvalidator/blob/master/README.md">Readme</a></li>
                </ul>
              </nav>
            </div>
          </div>

        <div class="inner cover">
            <h1 class="cover-heading"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Email Validator!</h1>

            <p class="lead">Email Validator is a free JSON API that gives you a simple way to validate/verify email addresses. You can get the free <a href="https://github.com/neo22s/emailvalidator/">source code and run it on your server</a> or use this service for free ;)</p>
            
            <p class="lead">
                <br>
                <form method="GET" class="form-inline">
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                    </div>                    
                    <button type="submit" class="btn btn-lg btn-default">Verify!</button>
                </form>
                <br>
            </p>

            <p class="lead">
                <h3>How to use it?</h3>
                <code>index.php/?email=some@email.com</code><br>
                OR<br>
                <code>emailvalidator::check('some@email.com');</code><br>
                <h3>How works?</h3>
                1. checks Email Format/Syntax<br>
                2. checks MX-Records (SMTP)<br>
                3. checks for Disposable Addresses<br>
                4. returns JSON<br>
                <code>{"valid":true,"message":"chema@gmail.com"}</code><br>
                <code>{"valid":false,"message":"Banned domain sharklasers.com"}</code>
            <p>

        </div>


          <div class="mastfoot">
            <div class="inner">
              <p>Made by <a href="http://garridodiaz.com">Chema</a> Thanks to <a href="https://github.com/ivolo/disposable-email-domains/">Disposable Emails</a></p>
            </div>
          </div>

        </div>

      </div>

    </div>

   
  </body>
</html>