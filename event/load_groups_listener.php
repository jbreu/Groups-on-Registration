<?php

namespace jbreu\groupsonregistration\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use phpbb\db\driver\driver_interface;

class load_groups_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents() 
	{
		return array(
			'core.ucp_register_data_before' 	=> 'ucp_register_data_before',
                        'core.ucp_register_data_after'		=> 'ucp_register_data_after',
			'core.user_add_after'			=> 'user_add_after',
			'core.memberlist_view_profile'		=> 'memberlist_view_profile',
		);
	}

        /* @var \phpbb\template\template */
        protected $template;
	
	/* driver */
    	private $db;

        /** @var \phpbb\request\request */
        protected $request;

        /** @var \phpbb\user */
        protected $user;

	protected $chosengroupid;

         /* Constructor
         *
         * @param \phpbb\template\template      $template       Template object
         */
        public function __construct(\phpbb\template\template $template, driver_interface $db, \phpbb\request\request $request, \phpbb\user $user)
        {
                $this->template = $template;
		$this->db = $db;
		$this->request = $request;
		$this->user = $user;
	
		$this->chosengroupid = -1;
	}


	public function ucp_register_data_before($event)
	{
	        $sql = 'SELECT group_id, group_name
        	        FROM ' . GROUPS_TABLE . '
                	WHERE group_type!=3';
       	 	$result = $this->db->sql_query($sql);

        	while ($row = $this->db->sql_fetchrow($result))
        	{
		 	$this->template->assign_block_vars('LOADED_GROUPS', array(
                        	'GROUPID'   => $row['group_id'],
                        	'GROUPNAME' => $row['group_name'],
                	));
        	}
        	$this->db->sql_freeresult($result);
	}

	public function ucp_register_data_after($event) 
	{
		$this->chosengroupid = $this->request->variable('korpogroups', '');
		//echo 'cgid: '.$this->chosengroupid."<br />";

		if ($this->chosengroupid == 999999)
                        $event['error'] = array('Sie haben keine Korporation ausgewählt!');
	}

	public function user_add_after($event) {
		//echo 'groupid: '.$this->chosengroupid."<br/>";
		//echo 'uid: '.$event['user_id']."<br/>";

		echo group_user_add($this->chosengroupid, $event['user_id'], false, false, false, /*$leader*/ 0, /*$pending*/ 1);
	}

	public function memberlist_view_profile($event) {
		 $sql = 'SELECT g.group_id, g.group_name, ug.user_pending
                        FROM ' . GROUPS_TABLE . ' as g
			INNER JOIN ' . USER_GROUP_TABLE . ' as ug ON g.group_id=ug.group_id 
                        WHERE g.group_type!=3 AND ug.user_id='.$event['member']['user_id'];
                $result = $this->db->sql_query($sql);

		$cnt=0;

                while ($row = $this->db->sql_fetchrow($result))
                {
			if ($row['user_pending']==0) {
				$cnt++;
                        	$this->template->assign_block_vars('MEMBER_GROUPS', array(
                                	'GROUPID'   	=> $row['group_id'],
                                	'GROUPNAME' 	=> $row['group_name'],
					'GROUPSTATUS' 	=> '',
                        	));
			} else {
                     		$cnt++;
                                $this->template->assign_block_vars('MEMBER_GROUPS', array(
                                        'GROUPID'       => $row['group_id'],
                                        'GROUPNAME'     => $row['group_name'],
                                        'GROUPSTATUS'   => ' (Aufnahme ausstehend)',
                                ));	
			}
                }

		$this->db->sql_freeresult($result);
		
		/* User did not (yet) join a non-special group */
		if ($cnt==0) {
			$sql = 'SELECT pf_phpbb_location as loc
                        	FROM ' . PROFILE_FIELDS_DATA_TABLE . '
                        	WHERE user_id='.$event['member']['user_id'];
                	$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result)) { 
				$this->template->assign_block_vars('MEMBER_GROUPS', array(
        	                        'GROUPID'   => '2',
                	                'GROUPNAME' => $row['loc'],
                        	));
			}

			$this->db->sql_freeresult($result);
		}
	}
}

?>
