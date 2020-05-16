<?php

namespace App\Services;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use App\Services\UserService;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Schema;

class TeamService
{
    /**
     * Team Model
     *
     * @var Team
     */
    public $model;

    /**
     * UserService
     *
     * @var UserService
     */
    protected $userService;
    /**
     * @var int
     */
    private $paginate;

    public function __construct(
        Team $model,
        UserService $userService
    ) {
        $this->model = $model;
        $this->userService = $userService;
        $this->paginate = 25;
    }

    /**
     * All teams
     *
     * @return Collection
     */
    public function all($userId)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')->get();
    }

    /**
     * All teams paginated
     *
     * @return LengthAwarePaginator
     */
    public function paginated($userId)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')->paginate($this->paginate);
    }

    /**
     * Search the teams
     *
     * @param integer $userId
     * @param string $input
     *
     * @return LengthAwarePaginator
     */
    public function search($userId, $input)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('teams');
        $query->where('id', 'LIKE', '%'.$input.'%');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%')->where('user_id', $userId);
        };

        return $query->paginate($this->paginate);
    }

    /**
     * Create a team
     *
     * @param integer $userId
     * @param array $input
     *
     * @return Team
     * @throws Exception
     */
    public function create($userId, $input)
    {
        try {
            $team = DB::transaction(function () use ($userId, $input) {
                $input['user_id'] = $userId;
                $team = $this->model->create($input);
                $this->userService->joinTeam($team->id, $userId);
                return $team;
            });

            return $team;
        } catch (Exception $e) {
            throw new Exception("Failed to create team", 1);
        }
    }

    /**
     * Find a team
     *
     * @param integer $id
     *
     * @return Team
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a team by name
     *
     * @param string $name
     *
     * @return Team
     */
    public function findByName($name)
    {
        return $this->model->where('name', $name)->firstOrFail();
    }

    /**
     * Update a team
     *
     * @param integer $id
     * @param array $input
     *
     * @return Team
     */
    public function update($id, $input)
    {
        $team = $this->model->find($id);
        $team->update($input);

        return $team;
    }

    /**
     * Delete a team
     *
     * @param User $user
     * @param integer $id
     *
     * @return boolean
     */
    public function destroy($user, $id)
    {
        if ($user->isTeamAdmin($id)) {
            $team = $this->model->find($id);
            foreach ($team->members as $member) {
                $this->userService->leaveTeam($id, $member->id);
            }
            return $this->model->find($id)->delete();
        }

        return false;
    }

    /**
     * Invite a team member
     *
     * @param User $admin
     * @param integer $id
     * @param string $email
     *
     * @return boolean
     * @throws Exception
     */
    public function invite($admin, $id, $email)
    {
        try {
            if ($admin->isTeamAdmin($id)) {
                $user = $this->userService->findByEmail($email);

                if (! $user) {
                    throw new Exception("We cannot find a user with this email address. You'll need to invite them to make an account on ".url('/'), 1);
                }

                if ($user->isTeamMember($id)) {
                    return false;
                }

                $this->userService->joinTeam($id, $user->id);

                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception("Failed to invite member", 1);
        }
    }

    /**
     * Remove a team member
     *
     * @param User $admin
     * @param integer $id
     * @param integer $userId
     *
     * @return boolean
     * @throws Exception
     */
    public function remove($admin, $id, $userId)
    {
        try {
            if ($admin->isTeamAdmin($id)) {
                $user = $this->userService->find($userId);

                if ($admin->isTeamAdmin($id)) {
                    $this->userService->leaveTeam($id, $user->id);
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            throw new Exception("Failed to remove member", 1);
        }
    }
}
