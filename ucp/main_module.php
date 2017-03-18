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
 * Groups on Registration UCP module.
 */
class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $request, $template, $user;

		$this->tpl_name = 'ucp_demo_body';
		$this->page_title = $user->lang('UCP_DEMO_TITLE');
		add_form_key('acme/demo');

		$data = array(
			'user_acme' => $request->variable('user_acme', $user->data['user_acme']),
		);

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('acme/demo'))
			{
				trigger_error($user->lang('FORM_INVALID'));
			}

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $db->sql_build_array('UPDATE', $data) . '
				WHERE user_id = ' . $user->data['user_id'];
			$db->sql_query($sql);

			meta_refresh(3, $this->u_action);
			$message = $user->lang('UCP_DEMO_SAVED') . '<br /><br />' . $user->lang('RETURN_UCP', '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);
		}

		$template->assign_vars(array(
			'S_USER_ACME'	=> $data['user_acme'],
			'S_UCP_ACTION'	=> $this->u_action,
		));
	}
}
