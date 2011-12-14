// by http://codewut.de/content/disable-close-button-dojo-dijitdialog

    dojo.provide("myWidgets.Dialog");
     
    dojo.declare
    (
    "myWidgets.Dialog",
    [dijit.Dialog],
    {
    // summary:
    // extended version of the dojo Dialog widget with the option to disable
    // the close button and supress the escape key.
     
    disableCloseButton: true,
     
    /* *********************************************************** postCreate */
    postCreate: function()
    {
    this.inherited(arguments);
    this._updateCloseButtonState();
    },
     
    /* *************************************************************** _onKey */
    _onKey: function(evt)
    {
    if(this.disableCloseButton && evt.charOrCode == dojo.keys.ESCAPE) return;
    this.inherited(arguments);
    },
     
    /* ************************************************ setCloseButtonDisabled*/
    setCloseButtonDisabled: function(flag)
    {
    this.disableCloseButton = flag;
    this._updateCloseButtonState();
    },
     
    /* ********************************************** _updateCloseButtonState */
    _updateCloseButtonState: function()
    {
    dojo.style(this.closeButtonNode,
    "display",this.disableCloseButton ? "none" : "block");
    }
    }
    );
    
    
    
function popup (url) {
   newwindow = window.open(url, "newwindow");
   newwindow.focus();
}