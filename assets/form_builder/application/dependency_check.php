<?php
/**
 * Created by 23rd and Walnut
 * www.23andwalnut.com
 * User: Saleem El-Amin
 * Date: 2/22/11
 * Time: 12:40 PM
 */


//If you do not want to perform the dependency check, set this to false
$check_dependencies = true;



if ($check_dependencies)
{
    $missing_dependency = null;

    //Check for the json_encode function
    if (!function_exists('json_encode'))
        $missing_dependency = '<p class="warning">This application requires the <strong>json_encode</strong> function please contact your host to upgrade your php version. </p>More info: <a target="_blank" href="http://us.php.net/manual/en/function.json-encode.php">json_encode</a><br/><br/>';

    //check for the zip extension
    if (!extension_loaded('zip'))
        $missing_dependency .= '<p class="warning">This application requires the <strong>Zip extension</strong> you will not be able to download zips unless you install this extension.</p>More info: <a target="_blank" href="http://us.php.net/manual/en/book.zip.php">Zip Extension</a><br/><br/>';

    //display error or success message
    if($missing_dependency != null)
        echo $missing_dependency;
    else echo "SUCCESS";
}
else echo "SUCCESS";



?>