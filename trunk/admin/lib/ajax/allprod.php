<?PHP
    session_start();
    require_once ('../json/jsonRPCClient.php');
    $rpc = new jsonRPCClient('https://'.$_SESSION['user'].':'.$_SESSION['pass'].'@'.$_SESSION['serv'].':4447/rpc', false);
    
    $check = $rpc->product_getHashes();
    
    foreach($check as $key => $value) {
        $check[$key]['productId'] = $check[$key]['id'];
    }
    
    echo "{
	identifier: 'productId',
	label: 'name',
	items: 
        ";
        echo json_encode($check);
    echo '}';
?>