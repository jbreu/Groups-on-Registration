<?php
/**
 *
 * Groups on Registration. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, jbreu
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace jbreu\groupsonregistration\acp;

/**
 * Groups on Registration ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\jbreu\groupsonregistration\acp\main_module',
			'title'		=> 'ACP_DEMO_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_DEMO',
					'auth'	=> 'ext_jbreu/groupsonregistration && acl_a_board',
					'cat'	=> array('ACP_DEMO_TITLE')
				),
			),
		);
	}
}
