<?PHP
	$title='OohJa! - OPSI ohne Java';
	session_start();
	
        require_once ('lib/json/jsonRPCClient.php');
	
	if(!isset($_SESSION['user']) || !isset($_SESSION['pass']) || !isset($_SESSION['serv']))
		header("Location: index.php?timeout");
	
	$rpc = @new jsonRPCClient('https://'.$_SESSION['user'].':'.$_SESSION['pass'].'@'.$_SESSION['serv'].':4447/rpc', false);
	if(!$rpc) {
	    header("Location: index.php?timeout");
	}else{
	    $check = $rpc->authenticated();
	    if(!$check) {
		header("Location: index.php?timeout");
	    }
	}
	
	require('tpl/header.php');
		
?>
<div dojoType="dojo.data.ItemFileWriteStore" jsId="prodStore"
		url="lib/ajax/allprod.php" clearOnClose="true" urlPreventCache="true"></div>
<div dojoType="dojo.data.ItemFileReadStore" jsId="hostStore"
		url="lib/ajax/hosts.php"></div>
<div dojoType="dojo.data.ItemFileWriteStore" jsId="hostPropStore"
		clearOnClose="true" urlPreventCache="true"></div>

<div dojoType="dijit.layout.LayoutContainer" id="main" style="width: 100%; height: 100%">
	<div dojoType="dijit.MenuBar" layoutAlign="top" style="height:25px;" id="navMenu">
		<div style="float:right"><img src="tpl/img/ico/drive.png" alt="Speichern" /></div>
		<div dojoType="dijit.MenuBarItem"><span><img src="tpl/img/logo-small.png" height="15" alt="OohJa!" tooltip="TEST" /></span></div>
		<div dojoType="dijit.PopupMenuBarItem"><span>System</span>
			<div dojoType="dijit.Menu" class="submenu1">
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'">Cache leeren</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='login.php?logout'">Abmelden</div>
			</div>
		</div>
		<div dojoType="dijit.PopupMenuBarItem"><span>PC-Aktionen</span>	
			<div dojoType="dijit.Menu" class="submenu1">
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">Client wecken (Wake on LAN)</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">On-Demand-Aktion auslösen</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">Nachricht auf Client anzeigen</div>
				<div dojoType="dijit.MenuSeparator"></div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">PC herunterfahren</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">PC neu starten</div>
				<div dojoType="dijit.MenuSeparator"></div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">PC löschen</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'">Neuer PC</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">PC umbenennen</div>
				<div dojoType="dijit.MenuItem" onclick="document.location.href='#'" disabled="true">PC in anderes Depot verschieben</div>
			</div>
		</div>
		<div dojoType="dijit.PopupMenuBarItem"><span>Info</span>
			<div dojoType="dijit.Menu" class="submenu1">
				<div dojoType="dijit.MenuItem" onclick="popup('http://download.uib.de/opsi_stable/doc/opsi-handbuch-stable-de.pdf');">OPSI-Dokumentation</div>
				<div dojoType="dijit.MenuItem" onclick="popup('https://forum.opsi.org/');">OPSI-Forum</div>
				<div dojoType="dijit.MenuSeparator"></div>
				<div dojoType="dijit.MenuItem" onclick="dijit.byId('dlg_about_opsi').show();">Systeminfo</div>
				<div dojoType="dijit.MenuItem" onclick="dijit.byId('dlg_about_oohja').show();">Über OohJa!</div>
			</div>
		</div>
	</div>
	
	<!--<div dojoType="dijit.layout.ContentPane" style="position:absolute; right:auto; bottom:auto;" region="center" role="document" aria-live="assertive" aria-atomic="true">
		<?PHP
			/*require_once ('lib/json/jsonRPCClient.php');
			$rpc = @new jsonRPCClient('https://'.$_SESSION['user'].':'.$_SESSION['pass'].'@'.$_SESSION['serv'].':4447/rpc', true, true);
			$test = new stdClass();
			$test->isMasterDepot = true;
			$test->type = 'OpsiDepotserver';
			$check = $rpc->host_getIdents(array(),$test);
			var_dump($check);
			echo 'sdfgsdgsdgsdg';
			$check = $rpc->getClients_listOfHashes();
			var_dump($check);
			
			phpinfo();*/
		?>
	</div>-->

		<div dojoType="dijit.layout.TabContainer" id="tabs" jsId="tabs" region="center">
			<!-- main section with tree, table, and preview -->
			<div dojoType="dijit.layout.BorderContainer" id="clientmanagement" title="Clientmanagement" design="sidebar">
				<div dojoType="dijit.layout.AccordionContainer" id="accordion"
						region="leading" minSize="20" style="width:33%;" splitter="true">
					<div dojoType="dijit.layout.AccordionPane" title="Clientliste">
						<table dojoType="dojox.grid.DataGrid"
							region="top" minSize="20" splitter="true"
							jsId="table2"
							id="table2"
							store="hostStore" 
							onRowClick="onHostClick"
							sortInfo="1"
							style="height: 150px; width: 100%;"
							width="100%">
							<thead>
								<tr>
									<th field="hostId">Hostname</th>
									<th field="ipAddress" width="30%">IP</th>
								</tr>
							</thead>
						</table> <!-- end of listPane -->
					</div>
					

				</div>  <!-- end of Accordion -->

				<div dojoType="dijit.layout.TabContainer" id="tabs_clientconfig" jsId="tabs_clientconfig" region="center" tabPosition="bottom">
					<!-- main section with tree, table, and preview -->
					<div dojoType="dijit.layout.BorderContainer" id="localboot" title="Lokale Produkte">
				
						
						<!-- list of products pane -->
						<table dojoType="dojox.grid.DataGrid"
							region="top" minSize="20" splitter="true"
							jsId="prodTable"
							id="prodTable"
							store="prodStore" 
							onRowClick="onProdClick"
							sortInfo="6"
							query="{ type:'LocalbootProduct' }"
							style="height: 50%; width: 100%;"
							width="100%">
							<thead>
								<tr>
									<th field="id">Ident</th>
									<th field="productVersionInstalled" width="10%">Version Installiert</th>
									<th field="packageVersionInstalled" width="10%">Paketersion Installiert</th>
									<th field="productVersion" width="10%">Version Verfügbar</th>
									<th field="packageVersion" width="10%">Paketersion Verfügbar</th>
									<th field="installationStatus" editable="true" cellType="dojox.grid.cells.Select" singleClickEdit="true" options="not_installed,installed,unknown" width="10%">Status</th>
									<th field="actionRequest" editable="true" cellType="dojox.grid.cells.Select" singleClickEdit="true" options="none,setup,update,uninstall,always,once" width="10%">Aktion</th> <!-- Da gibts noch mehr mögliche: Always, Once, ... -->
									<th field="productActionProgress" width="10%">Fortschritt</th>
								</tr>
							</thead>
						</table> <!-- end of listPane -->
		
						<!-- product details pane -->
						<div id="prod_details" dojoType="dijit.layout.ContentPane" tabindex="0" region="center" role="document" aria-live="assertive" aria-atomic="true" minSize="20">
							<p>
								This pane should contain some very interesting data. Unfortunaly im too lazy to implemet it at the moment...
							</p>
						</div> <!-- end of "message" -->
					</div> <!--Lokale Produkte-->
					<div dojoType="dijit.layout.BorderContainer" id="netboot" title="Netboot-Produkte">
						
						<!-- list of netproducts pane -->
						<table dojoType="dojox.grid.DataGrid"
							region="top" minSize="20" splitter="true"
							jsId="netprodTable"
							id="netprodTable"
							store="prodStore" 
							onRowClick="onProdClick"
							sortInfo="6"
							query="{ type:'NetbootProduct' }"
							style="height: 50%; width: 100%;"
							width="100%">
							<thead>
								<tr>
									<th field="id">Ident</th>
									<th field="productVersionInstalled" width="10%">Version Installiert</th>
									<th field="packageVersionInstalled" width="10%">Paketersion Installiert</th>
									<th field="productVersion" width="10%">Version Verfügbar</th>
									<th field="packageVersion" width="10%">Paketersion Verfügbar</th>
									<th field="installationStatus" editable="true" cellType="dojox.grid.cells.Select" singleClickEdit="true" options="not_installed,installed,unknown" width="10%">Status</th>
									<th field="actionRequest" editable="true" cellType="dojox.grid.cells.Select" singleClickEdit="true" options="none,setup,update,uninstall,always,once" width="10%">Aktion</th> <!-- Da gibts noch mehr mögliche: Always, Once, ... -->
									<th field="productActionProgress" width="10%">Fortschritt</th>
								</tr>
							</thead>
						</table> <!-- end of listPane -->
		
						<!-- product details pane -->
						<div id="netprod_details" dojoType="dijit.layout.ContentPane" tabindex="0" region="center" role="document" aria-live="assertive" aria-atomic="true" minSize="20">
							<p>
								This pane should contain some very interesting data. Unfortunaly im too lazy to implemet it at the moment...
							</p>
						</div> <!-- end of "message" -->
					</div>
					<div dojoType="dijit.layout.BorderContainer" id="hostprop" title="Host-Parameter">
						<!-- list of properties pane -->
						<table dojoType="dojox.grid.DataGrid"
							region="top"
							jsId="propTable"
							id="propTable"
							store="hostPropStore" 
							onRowClick="onHostPropClick"
							sortInfo="1"
							style="height: 100%; width: 100%;"
							width="100%">
							<thead>
								<tr>
									<th field="id">Property-Name</th>
									<th field="value" width="50%">Property-Wert</th>
								</tr>
							</thead>
						</table> <!-- end of listPane -->
					</div>
					<div dojoType="dijit.layout.BorderContainer" id="audit_hw" title="Hardwareaudit">
				<div dojoType="dijit.layout.ContentPane" region="top">
					<center id="servermanagementIndex">Diese Funktion wird noch nicht unterstützt!</center>
				</div></div>
					<div dojoType="dijit.layout.BorderContainer" id="audit_sw" title="Softwareaudit">
				<div dojoType="dijit.layout.ContentPane" region="top">
					<center id="servermanagementIndex">Diese Funktion wird noch nicht unterstützt!</center>
				</div></div>
					<div dojoType="dijit.layout.BorderContainer" id="log" title="Protokolle">
				<div dojoType="dijit.layout.ContentPane" region="top">
					<center id="servermanagementIndex">Diese Funktion wird noch nicht unterstützt!</center>
				</div></div>
				</div><!--Tabzeugs Mitte-->

			</div> <!-- end of clientmanagement -->
			
			<div dojoType="dijit.layout.BorderContainer" title="Depotmanagement">
				<div dojoType="dijit.layout.ContentPane" region="top">
					<center id="servermanagementIndex">Diese Funktion wird noch nicht unterstützt!</center>
				</div>
			</div>
			
			<div dojoType="dijit.layout.BorderContainer" title="Servermanagement">
				<div dojoType="dijit.layout.ContentPane" region="top">
					<center id="servermanagementIndex">Diese Funktion wird noch nicht unterstützt!</center>
				</div>
			</div>
			
			<div dojoType="dijit.layout.BorderContainer" title="Lizenzmanagement">
				<div dojoType="dijit.layout.ContentPane" region="top">
					<center id="servermanagementIndex">Diese Funktion wird noch nicht unterstützt!</center>
				</div>
			</div>

		</div> <!-- end of TabContainer -->
	
	<div dojoType="dijit.layout.ContentPane" layoutAlign="bottom" id="footer" align="left">
		<span style="float:right;">OohJa! 0.1 - Developement branch - (c) 2011 Florian Knodt</span>
		<div id="fetchMail" style="">
			<div annotate="true" id="fakeFetch" dojoType="dijit.ProgressBar" style="height:15px; width:275px;" indeterminate="true" report="fakeReport"></div>
		</div>
	</div>
</div> <!-- end of main -->
	
	<div id="dlg_about_oohja" dojoType="dijit.Dialog" title="Über OohJa!" href="tpl/about.php" style="text-align: center;">
		
	</div>
	
	<div id="dlg_about_opsi" dojoType="dijit.Dialog" title="Über OPSI" style="text-align: center;">
		<img src="tpl/img/logo.png" alt="OohJa - OPSI ohne Java" />
		<h1>OPSI <?php echo $_SESSION['cache']['info']['opsiVersion'].' @ '.$_SESSION['serv']; ?></h1>
		<p>(c) 2001-2011 by uib GmbH - <a href="http://www.uib.de">www.uib.de</a></p>
		<?PHP
			if(isset($_SESSION['cache']['info']['modules']['customer']) && $_SESSION['cache']['info']['modules']['customer'] != '') {
				$_SESSION['cache']['info']['modules']['expires-t']=strtotime($_SESSION['cache']['info']['modules']['expires']);
				echo '<p>Lizensiert für '.htmlentities($_SESSION['cache']['info']['modules']['customer']).'</p>
				
				<table class="columnlist">
					<tr>
						<th>Dynamische Depots</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
						<th>Vista/Win7-Support</th>
						<td><img src="tpl/img/ico/tick.png" /></td>
						<th>VPN-Support</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
					</tr>
					<tr>
						<th>Lizenzmanagement</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
						<th>Software-Kiosk</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
						<th>Multiplex</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
					</tr>
					<tr>
						<th>Treeview</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
						<th>MySQL-Backend</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
						<th>High Availability</th>
						<td><img src="tpl/img/ico/cross.png" /></td>
					</tr>
				</table>
				Gültig bis: '.strftime("%d.%m.%Y", $_SESSION['cache']['info']['modules']['expires-t']);
				if($_SESSION['cache']['info']['modules']['expires-t'] <= time()) {
					echo '<div class="error">Lizenz ist abgelaufen</div>';
				}elseif($_SESSION['cache']['info']['modules']['expires-t']-1209600 <= time()) {
					echo '<div class="warn">Lizenz läuft in Kürze ab</div>';
				}
				echo '<div class="warn">Diese Funktion zeigt keine realen Daten ueber Module</div>';
			}
		?>
		<p>Under General Public Licence (GPL)</p>
	</div>
</div>