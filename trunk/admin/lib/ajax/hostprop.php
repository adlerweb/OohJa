<?PHP
    session_start();
    require_once ('../json/jsonRPCClient.php');
    $rpc = new jsonRPCClient('https://'.$_SESSION['user'].':'.$_SESSION['pass'].'@'.$_SESSION['serv'].':4447/rpc/extend/configed', false);
    $check = $rpc->getConfigs(array($_GET['pc']));
    $check = $check[$_GET['pc']];
    
    $new=array();
    foreach($check as $key=>$value) {
	$new[]=array('id' => $key, 'value' => $value);
    }
    
    if(isset($_GET['debug'])) var_dump($new);
    echo "{
	identifier: 'id',
	label: 'id',
	items: 
        ";
        echo json_encode($new);
    echo '}';
?>