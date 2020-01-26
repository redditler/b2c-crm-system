<?php

namespace App\Support\UserRole;

interface UserRole
{
    /**
     * Method return all user children
    */
    public function getUserChildren();


    /**
     * return user regions
     */
    public function getUserRegion();

    /**
     * return user branches
     */
    public function getUserSalon();

}