<?php

namespace Framework;

use Framework\Seassion;

class Authorization
{
    /**
     * Check if the current logged in user owns a resource
     * @param int $resource
     * @return bool 
     * 
     */
    public static function isOwner($resourceId)
    {
        $sessionUser = Seassion::get('user');
        // inspectAndDie(isset($sessionUser['id']));
        if ($sessionUser !== null && isset($sessionUser['id'])) {

            $sessionUserId = (int) $sessionUser['id'];
            // inspectAndDie($resourceId);

            return $sessionUserId == $resourceId;
        } else {
            return false;
        }
    }
}
