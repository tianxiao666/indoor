FCKConfig.CustomConfigurationsPath = '' ;
FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'css/fck_editorarea.css' ;
FCKConfig.DocType = '' ;
FCKConfig.BaseHref = '' ;
FCKConfig.FullPage = false ;
FCKConfig.Debug = false ;
FCKConfig.AllowQueryStringDebug = true ;
FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/default/' ;
FCKConfig.PreloadImages = [ FCKConfig.SkinPath + 'images/toolbar.start.gif', FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif' ] ;
FCKConfig.PluginsPath = FCKConfig.BasePath + 'plugins/' ;
// FCKConfig.Plugins.Add( 'autogrow' ) ;
FCKConfig.AutoGrowMax = 400 ;
FCKConfig.ProtectedSource.Add( /<script[\s\S]*?\/script>/gi ) ;	// <SCRIPT> tags.
FCKConfig.ProtectedSource.Add( /<\?[\s\S]*?\?>/g ) ;	// PHP style server side code
FCKConfig.AutoDetectLanguage	= true ;
FCKConfig.DefaultLanguage		= 'zh-cn' ;
FCKConfig.ContentLangDirection	= 'ltr' ;
FCKConfig.ProcessHTMLEntities	= true ;
FCKConfig.IncludeLatinEntities	= true ;
FCKConfig.IncludeGreekEntities	= true ;
FCKConfig.FillEmptyBlocks	= true ;
FCKConfig.FormatSource		= true ;
FCKConfig.FormatOutput		= true ;
FCKConfig.FormatIndentator	= '    ' ;
FCKConfig.ForceStrongEm = true ;
FCKConfig.GeckoUseSPAN	= false ;
FCKConfig.StartupFocus	= false ;
FCKConfig.ForcePasteAsPlainText	= false ;
FCKConfig.AutoDetectPasteFromWord = true ;	// IE only.
FCKConfig.ForceSimpleAmpersand	= false ;
FCKConfig.TabSpaces		= 0 ;
FCKConfig.ShowBorders	= true ;
FCKConfig.SourcePopup	= false ;
FCKConfig.UseBROnCarriageReturn	= false ;	// IE only.
FCKConfig.ToolbarStartExpanded	= true ;
FCKConfig.ToolbarCanCollapse	= true ;
FCKConfig.IgnoreEmptyParagraphValue = true ;
FCKConfig.PreserveSessionOnFileBrowser = false ;
FCKConfig.FloatingPanelsZIndex = 10000 ;
FCKConfig.ToolbarLocation = 'In' ;
FCKConfig.ToolbarSets["Default"] = [
	['Source','DocProps','-','Save','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor', 'About']
] ;

FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','Smiley','Image','Flash','-','Link','Unlink']
] ;

FCKConfig.ToolbarSets["Normal"] = [
	['Bold','Italic','-','TextColor','BGColor','-','Smiley','Image','Flash','Link','Unlink','FontName','FontSize','-','About']
] ;
FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Flash','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','Table','Form'] ;
FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF' ;
FCKConfig.FontNames		= 'Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana' ;
FCKConfig.FontSizes		= '1/xx-small;2/x-small;3/small;4/medium;5/large;6/x-large;7/xx-large' ;
FCKConfig.FontFormats	= 'p;div;pre;address;h1;h2;h3;h4;h5;h6' ;
FCKConfig.StylesXmlPath		= FCKConfig.EditorPath + 'fckstyles.xml' ;
FCKConfig.TemplatesXmlPath	= FCKConfig.EditorPath + 'fcktemplates.xml' ;
FCKConfig.SpellChecker			= 'ieSpell' ;	// 'ieSpell' | 'SpellerPages'
FCKConfig.IeSpellDownloadUrl	= 'http://iespell.huhbw.com/ieSpellSetup220647.exe' ;
FCKConfig.MaxUndoLevels = 15 ;
FCKConfig.DisableObjectResizing = false ;
FCKConfig.DisableFFTableHandles = true ;
FCKConfig.LinkDlgHideTarget		= false ;
FCKConfig.LinkDlgHideAdvanced	= true ;
FCKConfig.ImageDlgHideLink		= false ;
FCKConfig.ImageDlgHideAdvanced	= true ;
FCKConfig.FlashDlgHideAdvanced	= true ;
var _FileBrowserLanguage	= 'asp' ;	// asp | aspx | cfm | lasso | perl | php | py
var _QuickUploadLanguage	= 'asp' ;	// asp | aspx | cfm | lasso | php
// Don't care about the following line. It just calculates the correct connector 
// extension to use for the default File Browser (Perl uses "cgi").
var _FileBrowserExtension = _FileBrowserLanguage == 'perl' ? 'cgi' : _FileBrowserLanguage ;
FCKConfig.LinkBrowser = false ;
FCKConfig.ImageBrowser = false;
FCKConfig.FlashBrowser = false;
FCKConfig.LinkUpload = false ;
FCKConfig.ImageUpload = false ;
FCKConfig.FlashUpload = false ;
FCKConfig.SmileyPath	= FCKConfig.BasePath + 'images/smiley/msn/' ;
FCKConfig.SmileyImages	= ['regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif','embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif','devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif','broken_heart.gif','kiss.gif','envelope.gif'] ;
FCKConfig.SmileyColumns = 8 ;
FCKConfig.SmileyWindowWidth		= 320 ;
FCKConfig.SmileyWindowHeight	= 240 ;