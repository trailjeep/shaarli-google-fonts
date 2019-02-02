<?php
/**
 * Shaarli google-fonts Plugin
 *
 * @author Jeff Sacco 
 * @link 
 */

/**
 * Init function: check settings, and set default format.
 *
 * @param ConfigManager $conf instance.
 *
 * @return array|void Error if config is not valid.
 */
function google_fonts_init($conf)
{
	if (!ini_get('allow_url_fopen')) {
		return ['allow_url_fopen must be enabled in php.ini'];
	}

	$hdlnfnt = $conf->get('plugins.GOOGLE_HEADLINE_FONT');
	if (empty($hdlnfnt)) { $conf->set('plugins.GOOGLE_HEADLINE_FONT', "Walter Turncoat"); }
	$bodyfnt = $conf->get('plugins.GOOGLE_BODY_FONT');
    if (empty($bodyfnt)) { $conf->set('plugins.GOOGLE_BODY_FONT', "IBM Plex Mono"); }
	$hdlnfbk = $conf->get('plugins.GOOGLE_HEADLINE_FALLBACK_FONT');
	if (empty($hdlnfbk)) { $conf->set('plugins.GOOGLE_HEADLINE_FALLBACK_FONT', "cursive"); }
	$bodyfbk = $conf->get('plugins.GOOGLE_BODY_FALLBACK_FONT');
    if (empty($bodyfbk)) { $conf->set('plugins.GOOGLE_BODY_FALLBACK_FONT', "monospace"); }

}

/**
 * Hook render_includes.
 * Executed on every page redering.
 *
 * Template placeholders:
 *   - css_files
 *
 * Data:
 *   - _PAGE_: current page
 *   - _LOGGEDIN_: true/false
 *
 * @param array $data data passed to plugin
 *
 * @return array altered $data.
 */
function hook_google_fonts_render_includes($data,$conf)
{
    // List of plugin's CSS files.
    // Note that you just need to specify CSS path.

    $api     = "https://fonts.googleapis.com/css?family=";
	$hdlnfnt = $conf->get('plugins.GOOGLE_HEADLINE_FONT');
	$hdlnlnk = str_replace(" ","+",$hdlnfnt);
	$bodyfnt = $conf->get('plugins.GOOGLE_BODY_FONT');
	$bodylnk = str_replace(" ","+",$bodyfnt);
    $hdlnfbk = $conf->get('plugins.GOOGLE_HEADLINE_FALLBACK_FONT');
    $bodyfbk = $conf->get('plugins.GOOGLE_BODY_FALLBACK_FONT');
	$url     = $api.$hdlnlnk."|".$bodylnk;
	$file    = PluginManager::$PLUGINS_PATH . '/google_fonts/google_fonts.css';
    $css     = <<< _EOT_
.pure-menu-item, .shaarli-title, .header-main, .link-header, h1, h2, h3, h4, h5, h6 { font-family: '$hdlnfnt', $hdlnfbk; }
body, #linklist-loop-content { font-family: '$bodyfnt', $bodyfbk; }
_EOT_;
	$hdrs = get_headers($url);
	if (stripos($hdrs[0],"200 OK")) {
		file_put_contents($file,$css);
		$data['css_files'][] = $url;
		$data['css_files'][] = $file;
	} else {
		$data['css_files'][] = '';
	}

    return $data;
}

/**
* This function is never called, but contains translation calls for GNU gettext extraction.
*/
function google_fonts_dummy_translation()
{
    // meta
    t('Use your preferred Google Fonts.');
}
