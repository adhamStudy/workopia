<?php

namespace Framework\Middleware;

use Framework\Seassion;

class Authorize
{

    /**
     * Check if the user is quthenticatd
     * @return bool
     * 
     */
    public function isAuthnticated()
    {
        return Seassion::has('user');
    }

    /**
     * Handle the users request
     * @param string $rold
     * @return bool 
     * 
     */
    public function handle($role)
    {
        if ($role === 'guest' && $this->isAuthnticated()) {
            return redirect('/');
        } else if ($role === 'auth' && !$this->isAuthnticated()) {
            return redirect('/auth/login');
        }
    }
}
