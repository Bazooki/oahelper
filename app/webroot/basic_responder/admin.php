<?php
/**
 * Created by PhpStorm.
 * User: chrisvv
 * Date: 2015/02/09
 * Time: 10:08 AM
 */

//include 'config.php';
//include 'functions.php';

//session_start();
//$now = time();
//
//$appID= Configure::read('appID');
//
//$appSecret= Configure::read('appSecret');

//DB stuff -------------------------------------------------------------------------------------------------------------
//$dbHandle = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']);
//$data = mysqli_query($dbHandle, "SELECT * FROM xml_logs ORDER BY created DESC");
//$num = mysqli_num_rows($data);


//END DB stuff -------------------------------------------------------------------------------------------------------------

//Send menu to API
if (isset($_POST['updateMenu'])) {


    $jsonMenu = json_encode(array(
        'button' => array(
            array(
                'type' => 'click',
                'name' => 'Daily Song',
                'key' => 'V1001_TODAY_MUSIC',
            ),
            array(
                'type' => 'click',
                'name' => 'Weekly Song',
                'key' => 'V1002_WEEKLY_MUSIC',
            ),
            array(

                'name' => 'Menu',
                'sub_button' => array(
                    array(
                        'type' => 'view',
                        'name' => 'Search',
                        'url' => 'http://www.google.com',

                    ),
                    array(
                        'type' => 'view',
                        'name' => 'Video',
                        'url' => 'http://www.youtube.com',

                    ),
                    array(
                        'type' => 'click',
                        'name' => 'Like Us',
                        'key' => 'V1001_GOOD',

                    )


                )
            )
        )
    ));

    $test = curl_send_json('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$_SESSION['accessToken'],$jsonMenu);

//Menu sent

//Echo response from API
    echo '<pre>';
    var_dump($test);
    echo '</pre>';

}


// Button
echo '<form action="admin.php" method="post">';
?>

    <input type = "submit" value = "Update Menu" name = "updateMenu" />
<?php
echo "<br />";
echo '</form>';



// Number of rows in xml_log table
echo ($num) . " total rows" . "<br><br>" ;


//$xml = simplexml_load_string($row['xml_in']);
$xml = Xml::build($this->XmlLog->find('all', array(

    'fields' => 'xml_in'

)));
print_r($xml);

// Check token expiry and gets new if expired
if (!isset($_SESSION['accessToken']) || $now >= $_SESSION['expires']){


    $tokenString = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appID.'&secret='.$appSecret);
    $tokenObj = json_decode($tokenString);


    $_SESSION['accessToken'] = $tokenObj->access_token;
    $_SESSION['expires'] = $tokenObj->expires_in + $now;

    //Shows token if retrieved
    var_dump($tokenObj);

}
else{

    echo 'Using existing token.';

}

echo '<br><br>';

//var_dump($_SESSION);


// Reply to user with push message
if (isset($_POST['submit'])) {

    $touser = $_POST['FromUserName'];

    $jsonOutput = json_encode(array(
        'touser' => $touser,
        'msgtype' => 'text',
        'text' => array(
            'content' => 'It works!'
        )

    ));


    $test = curl_send_json('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$_SESSION['accessToken'],$jsonOutput);
// end reply

    //Echo response from API
    echo '<pre>';
    var_dump($test);
    echo '</pre>';

}

//shows rows
while ($row = $data->fetch_array()) {
    $xml = simplexml_load_string($row['xml_in']);
    echo '<form action="admin.php" method="post">';
    echo $xml . '<br><br>';
    echo $row['created'] . " " . '<input type = "hidden" name = "id" value="' . $row['id'] . '">' . " -- " . '<input type="hidden" name="FromUserName" value = "' . $xml->FromUserName . '">'. $xml->FromUserName . " -- " . $xml->MsgType . " -- " . $xml->Content . '<input type = "submit" value = "Push" name = "submit" >';
    echo "<br />";
    echo '</form>';


}

$dbHandle->close();

