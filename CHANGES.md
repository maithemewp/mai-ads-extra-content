# Changelog

## 0.11.6 (2/24/21)
* Fixed: Rollback CMB2 to 2.7.0 to fix admin page url not working. See https://github.com/CMB2/CMB2/issues/1410.

## 0.11.5 (2/13/21)
* Added: Mai logo icon.
* Changed: Update CMB2 to 2.8.0

## 0.11.4 (12/11/20)
* Changed: Plugin header consistency.
* Changed: Update dependencies.

## 0.11.0 (6/30/20)
* Added: blockquote is now counted for in content ads and extra content.
* Added: `maiaec_count_content_elements` filter for which elements should be counted when displaying in content ads or extra content.
* Changed: Update CMB2 to 2.7.0.

## 0.10.0 (4/21/20)
* Changed: Update dependencies.

## 0.9.1 (1/3/20)
* Fixed: Entry content ads getting added inside other elements.

## 0.9.0 (7/25/19)
* Fixed: Issues when using certain HTML in content locations. Changed from `appendXml()` to adding new elements to the DOM.
* Fixed: Now safely deactivates and shows notice if not using a Genesis child theme.
* Changed: Dependencies now use Composer.

## 0.8.3 (12/5/18)
* Changed: Only process content (wpautop/embeds/etc) on the ad content itself, not on all the content when displaying in-content ads or extra content.

## 0.8.2 (10/25/18)
* Changed: Suppress errors due to malformed HTML.

## 0.8.1 (9/28/18)
* Fixed: Files only loading in backend.

## 0.8.0 (9/27/18)
* Changed: Count only top level div's and p's. Fixes issue counting inline <a> links.
* Changed: Only show errors if WP_DEBUG is true.

## 0.7.0 (7/11/18)
* Changed: Entry content ads now count top level elements, not just nested p's.

## 0.6.2 (6/15/18)
* Changed: Header/Footer locations now do_shortcode(), mostly for cookie notice shortcodes.

## 0.6.1 (5/16/18)
* Fixed: In content ads no longer counts the paragraphs of the previous inserted ads.
* Fixed: Widget C not displaying.

## 0.6.0 (5/7/18)
* Added: New field for opening `<body>` tag.

## 0.5.1 (1/30/18)
* Changed: Move deactivate_plugins() to admin_init hook.

## 0.5.0 (1/29/18)
* Changed: Convert display to procedural programming so easier to remove specific ad locations. This will allow custom templates to remove specific ads as-needed.

## 0.4.0 (1/16/18)
* Changed: Update with new repo location.

## 0.3.0 (1/5/18)
* Changed: Now works with any Genesis child theme!

## 0.2.2 (8/16/17)
* Fixed: Error when entry content ad location has content but no post types selected.

## 0.2.1 (8/16/17)
* Added: Widget fields heading.

## 0.2.0 (8/16/17)
* Added: "Before Footer" ad location.
* Added: Widget C.

## 0.1.2
* Added: Extra empty content check in ad display.

## 0.1.1
* Added: Widget A/B.

## 0.1.0
* Initial beta release.
