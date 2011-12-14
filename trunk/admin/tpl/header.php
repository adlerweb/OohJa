
<?PHP echo '<?xml version="1.0" encoding="ISO-8859-15"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?PHP echo $title; ?></title>
    <link rel="icon" href="/admin/favicon.ico" type="image/x-icon" />
    <style type="text/css">
        /* bring in the claro theme */
        @import "lib/dojo/dijit/themes/claro/claro.css";
        @import "lib/dojo/dijit/themes/claro/document.css";
      
        /* bring in the widget-specific CSS classes */
        @import "lib/dojo/dijit/themes/claro/layout/ContentPane.css";
        @import "lib/dojo/dijit/themes/claro/layout/TabContainer.css";
        @import "lib/dojo/dijit/themes/claro/layout/AccordionContainer.css.css";
        
        /* bring in custom styles */
        @import "tpl/style.css";
    </style>
    <script src="lib/dojo/dojo/dojo.js" type="text/javascript" djConfig="parseOnLoad:true"></script>
    <script type="text/javascript">
        dojo.require('dojox.validate');
        dojo.require('dojox.validate.us');
        dojo.require('dojox.validate.web');
        dojo.require('dijit.form.Form');
        dojo.require("dijit.form.Button");
        dojo.require("dojox.form.BusyButton");
        dojo.require("dojox.grid.DataGrid");
        dojo.require('dijit.form.ValidationTextBox');
        dojo.require("dijit.Dialog");
        dojo.require("dijit.Menu");
        dojo.require("dijit.MenuBar");
        dojo.require("dijit.MenuItem");
        dojo.require("dijit.PopupMenuItem");
        dojo.require("dijit.PopupMenuBarItem");
        dojo.require("dijit.ProgressBar");
        dojo.require("dijit.layout.LayoutContainer");
        dojo.require("dijit.layout.BorderContainer");
        dojo.require("dijit.layout.TabContainer");
        dojo.require("dijit.layout.AccordionContainer");
        dojo.require("dojo.data.ItemFileReadStore");
        dojo.require("dojo.data.ItemFileWriteStore");
        dojo.require("dojox.grid.cells._base");
        
        var fakeReport = function(percent){
                // FIXME: can't set a label on an indeterminate progress bar
                // like if(this.indeterminate) { return " connecting."; }
                return "Fetching: 1337 hosts...";
        }
                
        function onHostClick(cell){
            prodStore.url = "lib/ajax/hostprod.php?pc="+escape(cell.grid.getItem(cell.rowIndex).hostId[0]);
            prodStore.close();
            hostPropStore.url = "lib/ajax/hostprop.php?pc="+escape(cell.grid.getItem(cell.rowIndex).hostId[0]);
            hostPropStore.close();
            
            prodTable.setStore(prodStore, { type:'LocalbootProduct' });
            netprodTable.setStore(prodStore, { type:'NetbootProduct' });
            propTable.setStore(hostPropStore);
        }
        
        
        
        /**
        * Concatenates the values of a variable into an easily readable string
        * by Matt Hackett [scriptnode.com]
        * @param {Object} x The variable to debug
        * @param {Number} max The maximum number of recursions allowed (keep low, around 5 for HTML elements to prevent errors) [default: 10]
        * @param {String} sep The separator to use between [default: a single space ' ']
        * @param {Number} l The current level deep (amount of recursion). Do not use this parameter: it's for the function's own use
        */
       function print_r(x, max, sep, l) {
       
               l = l || 0;
               max = max || 10;
               sep = sep || ' ';
       
               if (l > max) {
                       return "[WARNING: Too much recursion]\n";
               }
       
               var
                       i,
                       r = '',
                       t = typeof x,
                       tab = '';
       
               if (x === null) {
                       r += "(null)\n";
               } else if (t == 'object') {
       
                       l++;
       
                       for (i = 0; i < l; i++) {
                               tab += sep;
                       }
       
                       if (x && x.length) {
                               t = 'array';
                       }
       
                       r += '(' + t + ") :\n";
       
                       for (i in x) {
                               try {
                                       r += tab + '[' + i + '] : ' + print_r(x[i], max, sep, (l + 1));
                               } catch(e) {
                                       return "[ERROR: " + e + "]\n";
                               }
                       }
       
               } else {
       
                       if (t == 'string') {
                               if (x == '') {
                                       x = '(empty)';
                               }
                       }
       
                       r += '(' + t + ') ' + x + "\n";
       
               }
       
               return r;
       
       };
       var_dump = print_r;

    </script>
    <script src="lib/customwidgets.js" type="text/javascript" ></script>
</head>
<body class="claro">