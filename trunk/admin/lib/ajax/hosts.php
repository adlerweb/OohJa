<?PHP
    session_start();
    require_once ('../json/jsonRPCClient.php');
    $rpc = new jsonRPCClient('https://'.$_SESSION['user'].':'.$_SESSION['pass'].'@'.$_SESSION['serv'].':4447/rpc', false);
    
    $check = $rpc->getClients_listOfHashes();
    echo "{ 
	identifier: 'hostId',
	label: 'hostId',
	items: 
        ";
        echo json_encode($check);
    echo '}';
?>