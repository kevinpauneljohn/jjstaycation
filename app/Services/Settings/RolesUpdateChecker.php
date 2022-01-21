<?php

namespace App\Services\Settings;

class RolesUpdateChecker
{

    /**
     * This will check if the submitted role for the permission was changed
     * @param $submittedRole
     * @param $savedRole
     * @param int $change
     * @return int
     */
    public function rolesUpdateChecker($submittedRole, $savedRole, int $change = 0): int
    {
        if($submittedRole !== null )
        {
            if(collect($submittedRole)->count() >= collect($savedRole)->count())
            {
                foreach ($submittedRole as $role){
                    if(!in_array($role, collect($savedRole)->toArray()))
                    {
                        $change++;
                    }
                }
            }else{
                $change++;
            }
        }else{
            if(!in_array("", collect($savedRole)->toArray()))
            {
                $change++;
            }
        }

        //if both submitted roles and saved roles are empty
        if(collect($submittedRole)->count() === 0 && collect($savedRole)->count() === 0)
        {
            $change = 0;
        }

        return $change;
    }
}
