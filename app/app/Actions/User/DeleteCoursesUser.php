<?php

namespace App\Actions\User;

use App\Models\User;

class DeleteCoursesUser
{
    public function handle(User $item, array $courses): User
    {
        $item->courses()->detach($courses);

        return $item;
    }
}
