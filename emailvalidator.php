<?
/**
 * Class email validator
 *
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2015
 * @license    GPL v3
 */
class emailvalidator
{
    /**
     * verify email format, dns and banned emails
     * @param  string $email 
     * @return mixed   bool true if correct / string
     */
    public static function check($email)
    {
        //get the email to check up, clean it
        $email = filter_var($email,FILTER_SANITIZE_STRING);

        // 1 - check valid email format using RFC 822
        if (filter_var($email, FILTER_VALIDATE_EMAIL)===FALSE) 
            return 'No valid email format';
            
        //get email domain to work in nexts checks
        $email_domain = preg_replace('/^[^@]++@/', '', $email);

        // 2 - check if its from banned domains.
        if (in_array($email_domain,self::get_banned_domains()))
            return 'Banned domain '.$email_domain;
              
        // 3 - check DNS for MX records
        if ((bool) checkdnsrr($email_domain, 'MX')==FALSE)
            return 'DNS MX not found for domain '.$email_domain;

        // 4 - wow actually a real email! congrats ;)
        return TRUE;
    }

    /**
     * gets the array of not allowed domains for emails, reads from json stores file for 1 week
     * @return array 
     * @see banned domains https://github.com/ivolo/disposable-email-domains/blob/master/index.json
     * @return array
     */
    private static function get_banned_domains()
    {
        //where we store the banned domains
        $file = 'banned_domains.json';

        //if the json file is not in local or the file exists but is older than 1 week, regenerate the json
        if (!file_exists($file) OR (file_exists($file) AND filemtime($file) < strtotime('-1 week')) )
        {
            $banned_domains = file_get_contents("https://rawgit.com/ivolo/disposable-email-domains/master/index.json");
            if ($banned_domains !== FALSE)
                file_put_contents($file,$banned_domains,LOCK_EX);
        }
        else//get the domains from the file
            $banned_domains = file_get_contents($file);

        return json_decode($banned_domains);
    }
}