<?php

namespace App\Actions\User;

use App\Models\User;

class CreateCoursesUser
{
    public function handle(User $item, array $courses): User
    {
        foreach ($courses as $course) {
            $added = $course["added"] === "1" ? true : false;

            if ($added) {
                $item->courses()->attach($course["id"]);
            }
        }

        $item->save();

        return $item;
    }
}
