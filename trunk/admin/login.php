<?PHP
    if(!isset($_SESSION))  session_start();
    $title = 'OohJa! - Login';
    
    $logout=false;
    if(isset($_GET['logout'])) {
        session_destroy();
        session_start();
        $logout=true;
    }
    
    $timeout=false;
    if(isset($_GET['timeout'])) {
        session_destroy();
        session_start();
        $timeout=true;
    }
    
    $loginerror=false;
    if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['serv'])) {
        
        $check = @fopen('https://'.$_POST['user'].':'.$_POST['pass'].'@'.$_POST['serv'].':4447/rpc', "r");
        if(!$check) {
            $loginerror = true;
        }else{
            require_once ('lib/json/jsonRPCClient.php');
            $rpc = @new jsonRPCClient('https://'.$_POST['user'].':'.$_POST['pass'].'@'.$_POST['serv'].':4447/rpc', false, true);
            if($rpc) {
                try {
                    /*var_dump(time());
                    $arg=array('attributes' => '[]', 'filter' => '{ "type":"OpsiOpsiDepotserver" }');
                    var_dump($arg);
                    $check = $rpc->host_getObjects($arg);
                    var_dump($check);*/
                    $check = $rpc->authenticated();
                    if(!$check) {
                        $loginerror=true;
                    }else{
                        $info = $rpc->getOpsiInformation_hash();
                        $_SESSION['user']=$_POST['user'];
                        $_SESSION['pass']=$_POST['pass'];
                        $_SESSION['serv']=$_POST['serv'];
                        $_SESSION['cache']['info']=$info;
                        /*echo 'Angemeldet als '.htmlentities($_POST['user']).' | Verbunden mit OPSI '.htmlentities($info['opsiVersion']).' an Server '.htmlentities($_POST['serv']).' | Kunde: '.htmlentities($info['modules']['customer']).' | [<a href="?logout">Logout</a>]';*/
                        //die();
                        header("Location: test.php");
                    }
                } catch (Exception $e) {
                    $loginerror = true;
                }
            }
        }
        
        
    }
    
    
    require('tpl/header.php');
?>
    <div id="dlg_login" dojoType="myWidgets.Dialog" title="<?PHP echo $title; ?>" style="text-align: center;">
        <img src="tpl/img/logo.png" alt="OohJa - OPSI ohne Java" />
        
        <?PHP
        if($loginerror) echo '<div class="error">Fehler: Benutzer oder Kennwort falsch!</div>';
        if($logout) echo '<div class="info">Sie wurden erfolgreich abgemeldet!</div>';
        if($timeout) echo '<div class="info">Ihre Sitzung ist abgelaufen. Bitte melden sie sich neu an.</div>';
        ?>
        
        <form dojoType="dijit.form.Form" action="login.php" method="post">
            <script type="dojo/method" event="onSubmit">
                if (!this.validate()) {
                    alert('Bitte prüfen sie ihre Eingaben!');
                    return false;
                }
                document.getElementById('loading').style.display='block';
                return true;
            </script>
            <table cellpadding="0" cellspacing="4">
                <tr>
                    <th>
                        <strong>Benutzername:</strong>
                    </th>
                    <td>
                        <input name="user" placeholder="Benutzername" required="true" id="user" dojotype="dijit.form.ValidationTextBox" type="text" missingMessage="Bitte geben sie ihren Benutzernamen ein!" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <strong>Kennwort:</strong>
                    </th>
                    <td>
                        <input name="pass" placeholder="Kennwort" required="true" id="pass" dojotype="dijit.form.ValidationTextBox" type="password" missingMessage="Bitte geben sie ihr Passwort ein!" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <strong>Server:</strong>
                    </th>
                    <td>
                        <input name="serv" value="localhost" required="true" id="serv" dojotype="dijit.form.ValidationTextBox" type="text" missingMessage="Bitte geben sie die Serveradresse ein!" />
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <input value="Login" label="Login" id="submitButton" dojotype="dijit.form.Button" type="submit" />
                        <img src="lib/dojo/dijit/themes/claro/images/treeExpand_loading.gif" id="loading" alt="lade..." style="display: none;" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
    <script type="text/javascript">
        dojo.addOnLoad(function(){ 
            dijit.byId("dlg_login").show();
        });
    </script>
</body>
</html>
