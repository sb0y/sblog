(function () {
    tinymce.create('tinymce.plugins.CodeSnippetPlugin', {
        getInfo: function () {
            return {
                longname: 'Code Snippet',
                author: '',
                authorurl: '',
                infourl: '',
                version: "1.0"
            };
        },

        init: function (ed, url) {
            var t = this;

            // Register commands
            ed.addCommand('mceToggleCodeSnippet', function () {

                //drop in code block
                var code = ed.selection.getContent();
                if (ed.selection.getNode().nodeName != 'CODE') {
                    if (code == "") {
                        //drop in new code block and set cursor appropriately
                        if (tinyMCE.isIE) {
                            ed.execCommand("mceInsertContent", false, "<pre><code> </code></pre> ");
                            var snippets = ed.dom.select('code');
                            for (i = 0; i < snippets.length; i++) {
                                if (snippets[i].innerText == " ") {
                                    ed.selection.select(snippets[i]);
                                    break;
                                }
                            }
                        } else if (tinyMCE.isWebKit) {
                            ed.execCommand("mceInsertContent", false, "<pre><code> </code></pre> ");
                            var snippets = ed.dom.select('code');
                            if (snippets && snippets.length == 1)
                                ed.selection.select(ed.dom.select('code')[0]);
                            else
                                ed.selection.select(ed.selection.getSel().anchorNode.previousSibling.children[0])
                        } else {
                            ed.selection.setContent('<pre><code><span> </span></code></pre> ');
                            ed.selection.select(ed.dom.select('span')[0]);
                        }
                    }
                    else
                        ed.selection.setContent('<pre><code>' + code + '</code></pre>'); //code block selection
                }
                else {
                    //drop the cursor after the current code block
                    if (tinyMCE.isWebKit || tinyMCE.isIE) {
                        var parents = tinymce.DOM.getParent(ed.selection.getNode(), "pre");
                        if (parents[parents.length - 1])
                            ed.selection.select(parents[parents.length - 1]);
                        else
                            ed.selection.select(parents);
                    }
                    else
                        ed.selection.select(tinymce.DOM.getParent(ed.selection.getNode(), "pre"));
                    ed.selection.collapse();
                }

                ed.nodeChanged();
            });

            // Register button
			ed.addButton('codesnippet', {
				title : 'example.desc',
				cmd : 'mceToggleCodeSnippet',
				image : url + '/img/flash.gif'
			});

            // Selects the button in the UI when a code tag is selected
            ed.onNodeChange.add(function (ed, cm, n) {
                cm.setActive('codesnippet', n.nodeName == 'CODE' || tinymce.DOM.getParent(n, "pre") != null);
            });

        },

        createControl: function (n, cm) {
            var t = this, c, ed = t.editor;

            if (n == 'codesnippet') {
                c = cm.createButton(n, { title: 'Insert Code Block', cmd: 'mceToggleCodeSnippet', scope: t });

                return c;
            }
        }
    });
    // Register plugin
    tinymce.PluginManager.add('codesnippet', tinymce.plugins.CodeSnippetPlugin);
})();
