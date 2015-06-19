<?php
class Owncloud_Listeners
{
    /**
     * Event listener for 'module.users.ui.process_delete' event.
     *
     * @param Zikula_Event $event
     *
     * @return void
     */
    public function deleteOwncloudUser(Zikula_Event $event)
    {
        ModUtil::apiFunc('Owncloud', 'deleteUser', 'toDelete', array('uid' => $event->getArg('id')));
    }

}
