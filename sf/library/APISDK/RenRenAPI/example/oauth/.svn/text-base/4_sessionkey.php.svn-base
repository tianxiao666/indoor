<?php
require_once '../../requires.php';

$oauth = new RenRenOauth();
$access_token = $_GET['renren_access'];
$key = $oauth->getSessionKey($access_token);
var_dump($key);
echo '<br/><br/>';
?>
<a href="../api/5_api.php?session_key=<?php echo $key['renren_access']['session_key']; ?>">Test Api</a>