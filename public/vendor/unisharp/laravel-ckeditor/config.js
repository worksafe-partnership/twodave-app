/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'document',	   groups: [ 'document', 'doctools' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup', 'Font', 'FontSize' ] },
        { name: 'clipboard', groups: [ 'undo', 'redo' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent' ] },
		{ name: 'styles' },
		{ name: 'colors' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript,Cut,Copy,Paste,PasteFromWord,PasteText,Styles,FontSize';
    config.extraPlugins = 'font,undo';
    config.removePlugins = 'elementspath';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';
    config.font_defaultLabel = 'Arial';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};

CKEDITOR.on("instanceReady", function (ev) {
    var editor = ev.editor;
    editor.on("paste", function (evt) {
        evt.stop();
        var data = '<span style="font-family:Arial,Helvetica,sans-serif">' + evt.data.dataValue + '</span>';
        evt.editor.insertHtml(data);
    });
});
