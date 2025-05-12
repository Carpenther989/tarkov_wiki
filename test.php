<?php
$str = 'gmail@gmail.com';
$email = $str;
if(validateMail($email))
{
    echo 'tru dat shit';
}
else{
    echo 'vskutku s čepicí';
}




function validateMail($mail) {
    if(filter_var($mail, FILTER_VALIDATE_EMAIL)==true)
    {
        return true;
    }
    else{
        return false;
    }
}

function  emailToJson($mail): string
{
    $split = explode("@", $mail);
    $host = $split[0];
    $splitDomain = explode('.', $split[1]);
    $tld = $splitDomain[sizeof($splitDomain) - 1];
    $domain = '';
    for ($i = 0; $i < sizeof($splitDomain)-1; $i++) {
    $domain=  $domain.'.'.$splitDomain[$i];
    }

    $domain=substr_replace($domain,'',0,1);


    $test = $host.'@'.$domain.'.'.$tld;
    if($test == $mail)
    {//echo true;
    }

    $output = '[{ "username":"'.$host.'", "domain":"'.$domain.'", "tld":"'.$tld.'"}]';
   // echo $output;
    return $output;

}

?>