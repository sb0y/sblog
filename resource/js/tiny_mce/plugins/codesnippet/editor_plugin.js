(function () {
    tinymce.create('tinymce.plugins.CodeSnippetPlugin', {
		url: '', ed: null,
		
        getInfo: function () {
            return {
                longname: 'Code Snippet',
                author: 'Sb0y',
                authorurl: 'http://www.bagrincev.ru',
                infourl: '',
                version: "1.0"
            };
        },

        init: function (ed, url) {
            var t = this; this.url = url; this.ed = ed;

            // Selects the button in the UI when a code tag is selected
            ed.onNodeChange.add(function (ed, cm, n) {
                cm.setActive('codesnippet', n.nodeName == 'CODE' || tinymce.DOM.getParent(n, "pre") != null);
            });

        },

_codelight: function (lang) 
	{
		var ed = this.ed;
		
		var startEl = ed.selection.getStart();
		var endEl = ed.selection.getEnd();

		var textBegin = document.createTextNode ("[code="+lang+"]");
		var textEnd = document.createTextNode ("[/code]");

		startEl.parentNode.insertBefore (textBegin, startEl);
		tinymce.DOM.insertAfter (textEnd, endEl);
	},

 createControl: function (n, cm) {
	var t = this; var ed = t.ed;
	if (n=='codelight')
	{
		var c = cm.createSplitButton('codelight', {
			title : 'Подсветить код',
			image : this.url+'/img/code.gif',
			onclick : function() {
				c.showMenu();
			}
		});

	c.onRenderMenu.add(function(c, m) {
		m.add({title : 'Подсветить код', 'class' : 'mceMenuItemTitle'}).setDisabled(1);

		m.add({title : 'PHP', onclick : function() {
			t._codelight('php');
		}});
		m.add({title : 'Perl', onclick : function() {
			t._codelight('perl');
		}});
		m.add({title : 'MySQL', onclick : function() {
			t._codelight('mysql');
		}});
		m.add({title : 'Java Script', onclick : function() {
			t._codelight('javascript');
		}});
		m.add({title : 'HTML', onclick : function() {
			t._codelight('html4strict');
		}});
		m.add({title : 'CSS', onclick : function() {
			t._codelight('css');
		}});
		m.add({title : 'XML', onclick : function() {
			t._codelight('xml');
		}});
		m.add({title : 'shell (Bash)', onclick : function() {
			t._codelight('bash');
		}});
		m.add({title : 'C', onclick : function() {
			t._codelight('c');
		}});
		m.add({title : 'C++', onclick : function() {
			t._codelight('cpp');
		}});
		m.add({title : 'C++ с Qt', onclick : function() {
			t._codelight('cpp-qt');
		}});
		m.add({title : 'Другие языки', onclick : function() {
			//tinyMCE.activeEditor.windowManager.open ({file:'http://www.ru',maximizable:true,scrollbars:true});
			window.open('http://www.mediawiki.org/wiki/Extension:SyntaxHighlight_GeSHi#Supported_languages');
		}});
	});

	// Return the new splitbutton instance
	return c;
	}

	return null;
}

});
// Register plugin
tinymce.PluginManager.add('codesnippet', tinymce.plugins.CodeSnippetPlugin);
})();
