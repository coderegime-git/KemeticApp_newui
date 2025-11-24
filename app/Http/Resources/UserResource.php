<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'rate' => (string)$this->rates(),
            'headline' => $this->headline,
            'public_message' => (bool)$this->public_message,
            'offline' => (bool)$this->offline,
            'offline_message' => $this->offline_message,
            'verified' => (bool)$this->verified,
            'followers_count' => (string)$this->followers()->count(),
            'following_count' => (string)$this->following()->count(),
            'badges' => $this->badges,
            'auth_user_is_follower' => $this->authUserIsFollower ? $this->authUserIsFollower : $this->userFollowerStatus,
            'about' => $this->about,
            'seller_url' => url($this->getAvatar()),
            'course_progress_count' => $this->course_progress,
            'passed_quizzes_count' => $this->passed_quizzes,
            'unsent_assignments_count' => $this->unsent_assignments,
            'pending_assignments_count' => $this->pending_assignments,

        ];
    }
}
