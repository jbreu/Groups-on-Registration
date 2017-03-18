<?php
/**
 *
 * Groups on Registration. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, jbreu
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace jbreu\groupsonregistration\ucp;

/**
 * Groups on Registration UCP module info.
 */
class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\jbreu\groupsonregistration\ucp\main_module',
			'title'		=> 'UCP_DEMO_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'UCP_DEMO',
					'auth'	=> 'ext_jbreu/groupsonregistration',
					'cat'	=> array('UCP_DEMO_TITLE')
				),
			),
		);
	}
}
