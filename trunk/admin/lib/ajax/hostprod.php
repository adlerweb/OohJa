<?PHP
    session_start();
    require_once ('../json/jsonRPCClient.php');
    $rpc = new jsonRPCClient('https://'.$_SESSION['user'].':'.$_SESSION['pass'].'@'.$_SESSION['serv'].':4447/rpc', false);
    
    $check = $rpc->getLocalBootProductStates_hash(array($_GET['pc']));
    $check2 = $rpc->product_getHashes();
    $check3 = $rpc->getNetBootProductStates_hash(array($_GET['pc']));
    
    $haystack = array_prod_rekey($check);
    $haystack3 = array_prod_rekey($check3);
    
    $new=array();
    foreach($check2 as $key => $value) {
        if(isset($haystack[$value['id']])) {
            $haystack[$value['id']]['productVersionInstalled'] = $haystack[$value['id']]['productVersion'];
            unset($haystack[$value['id']]['productVersion']);
            $haystack[$value['id']]['packageVersionInstalled'] = $haystack[$value['id']]['packageVersion'];
            unset($haystack[$value['id']]['packageVersion']);
            $new[$value['id']] = array_merge($check2[$key], $haystack[$value['id']]);
        }elseif(isset($haystack3[$value['id']])) {
            $haystack3[$value['id']]['productVersionInstalled'] = $haystack3[$value['id']]['productVersion'];
            unset($haystack3[$value['id']]['productVersion']);
            $haystack3[$value['id']]['packageVersionInstalled'] = $haystack3[$value['id']]['packageVersion'];
            unset($haystack3[$value['id']]['packageVersion']);
            $new[$value['id']] = array_merge($check2[$key], $haystack3[$value['id']]);
        }else{
            $new[$value['id']] = $value;
        }
    }
    
    if(isset($_GET['debug'])) var_dump($new);
    
    ksort($new);
    $new2=array();
    foreach($new as $value) {
        $new2[]=$value;
    }
    
    echo "{
	identifier: 'id',
	label: 'id',
	items: 
        ";
        echo json_encode($new2);
    echo '}';
    
    function array_prod_rekey($haystack) {
        $new=array();
        $haystack=$haystack[$_GET['pc']];
        foreach($haystack as $value) {
            $new[$value['productId']]=$value;
        }
        return $new;
    }
?>