CONTENTS OF THIS FILE
---------------------

* Introduction
* Requirements
* Installation
* Configuration


INTRODUCTION
------------

Drupal 8.8 core adds support for embedding media via the media library in a WYSIWYG. This sandbox module adds a plugin that extends core functionality to provide a widget for an inline media element.


REQUIREMENTS
------------

This module requires
 * Drupal core >= 8.8. 
 See https://www.drupal.org/project/drupal/releases/8.8.x-dev


INSTALLATION
------------

Install the module as you would normally install a sandbox Drupal module. Visit https://www.drupal.org/docs/8/extending-drupal-8/installing-sandbox-modules for further information. Ensure CKeditor, Media, Media Library, and this module are enabled.


CONFIGURATION
--------------

1. Navigate to Administration > Configuration > Content authoring > Text formats and editors and select "Configure" for the formatter you would like to add the plugin to. 
2. Add both "Insert from Media Library" and "Insert inline from Media Library" buttons to toolbar configuration for CKEditor.
3. Enable both the Embed Media and Embed Media Inline filters and configure allowed media types and view modes and save.
4. Navigate to Administration > Structure > Media types and manage display for any media types you would like to display inline and under WYSIWYG Options check embed inline and save. This is necessary to trigger an inline embed theme suggestion eliminating block elements in HTML. Otherwise the widget will be stripped by ckeditor
    
