/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'vi';
         config.skin = 'office2013';
//         http://stackoverflow.com/questions/18499097/ckeditor-getdata-returns-html-character-entities-unicode-but-how-does-one-g
         config.entities_latin = false;
         config.entities_greek = false;
	// config.uiColor = '#AADC6E';
};
