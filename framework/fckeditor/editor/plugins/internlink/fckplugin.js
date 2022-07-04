FCKPlugins.Items['internlink'].Path = FCKConfig.PluginsPath + 'internlink/'
// Register the related command.
FCKCommands.RegisterCommand( 'internlink', new FCKDialogCommand( FCKLang['internlinkTitle'], FCKLang['internlinkTitle'], FCKConfig.PluginsPath + 'internlink/fck_internlink.php', 480, 600 ) ) ;

// Create the "Plaholder" toolbar button.
var ointernlink = new FCKToolbarButton( 'internlink', FCKLang['internlinkBtn'] ) ;
ointernlink.IconPath =  FCKConfig.PluginsPath + 'internlink/placeholder.gif' ;

FCKToolbarItems.RegisterItem( 'internlink', ointernlink ) ;


// The object used for all Placeholder operations.
var FCKinternlink = new Object() ;
// Add a new placeholder at the actual selection.
FCKinternlink.Add = function( name )
{
	FCK.InsertHtml('[['+name+']]');
}


