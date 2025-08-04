# Navigation block plugin
A sidebar block plugin for [Open Journal Systems](https://github.com/pkp/ojs) that allows the user to navigate 
by articles and issues.

## About
This plugin creates an additional sidebar block. When added to a sidebar, it displays navigation tools for browsing 
issues and articles.

## License
This plugin is licensed under the GNU General Public License v3. See the file
LICENSE for the complete terms of this license.

## Install
 * Go to Settings -> Website -> Plugins -> Plugin Gallery -> Click in **Navigation Block Plugin** -> Install.
 * In next page, enable the plugin.
 * Go to Settings -> Website -> Appearance -> Sidebar management -> and drag the " Plugin" to the sidebar section.

## Compatibility
This plugin is compatible with OJS 3.4.0-x.

## Styling
In directory styles_examples, there is a navigation.less example that can be used e.g. with the default theme or a 
or a theme plugin derived from the default theme.  The example
 * makes use of the fontawesome icons which are included with OJS
 * hides the issue navigation in "Browse Articles"
 * hides the navigation to the first and last article in "Browse Articles"
 * hides the displayed issue and the navigation to the first and last issue in "Browse Issues"

Copy navigation.less to the styles or styles/components directory and include it in the styles/index.less file of
your theme.

## Credits
Developed and maintained by mpbraendle at https://github.com/mpbraendle/navigation-block

