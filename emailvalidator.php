<?
/**
 * Class email validator
 *
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2023
 * @license    GPL v3
 * @version    2.0
 */
class emailvalidator
{
    /**
     * Verify email format, DNS, and banned emails
     * @param  string $email 
     * @return mixed   bool true if correct / string
     */
    public static function check($email)
    {
        // Get the email to check up, clean it
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        // 1 - Check valid email format using RFC 822
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) 
            return 'No valid email format';
            
        // Get email domain to work in next checks
        $email_domain = preg_replace('/^[^@]++@/', '', $email);

        // 2 - Check valid domain name
        if (filter_var($email_domain, FILTER_VALIDATE_DOMAIN) === FALSE) 
            return 'No valid domain format';

        // 3 - Check if it's from banned domains.
        $banned_domains = self::get_banned_domains();
        if (isset($banned_domains[$email_domain]) AND $banned_domains[$email_domain] !== null) {
            return 'Banned domain ' . $email_domain;
        }
              
        // 4 - Check DNS for MX records
        if ((bool) checkdnsrr($email_domain, 'MX') == FALSE)
            return 'DNS MX not found for domain '.$email_domain;

        // 5 - Wow, actually a real email! Congrats ;)
        return TRUE;
    }

    /**
     * Gets the array of not allowed domains for emails, reads from JSON stores file for 1 week
     * @return array 
     * @see banned domains https://github.com/ivolo/disposable-email-domains/blob/master/index.json
     * @return array
     */
    private static function get_banned_domains()
    {
        $banned_domains = [];
       
        $file = plugin_dir_path(self::MAIN_FILE).'banned-domains.php';

        if (!file_exists($file) OR (file_exists($file) AND filemtime($file) < strtotime('-1 month'))) {
            // If the file doesn't exist or is older than a month, regenerate it
            $banned_domains = file_get_contents("https://rawgit.com/ivolo/disposable-email-domains/master/index.json");

            // we could read the CDN file
            if ($banned_domains !== FALSE) 
            {
                $banned_domains = json_decode($banned_domains, true);

                //error reading the JSON file
                if ($banned_domains === null AND json_last_error() !== JSON_ERROR_NONE) 
                    return [];

                // Use array_filter with an inline closure function to remove invalid domains, just in case....
                $banned_domains = array_filter($banned_domains, function ($domain) {
                    return filter_var($domain, FILTER_VALIDATE_DOMAIN) !== false;
                }, ARRAY_FILTER_USE_KEY);

              
                // Store the banned domains as an associative array with domains as keys
                $banned_domains = array_fill_keys(array_keys(array_flip($banned_domains)), 0);

                file_put_contents($file, '<?php return ' . var_export($banned_domains, true) . ';', LOCK_EX);
            }
        }
        else// Load the domains from the cached PHP file
            $banned_domains = include($file);
        
        return $banned_domains;
    }
    
}
