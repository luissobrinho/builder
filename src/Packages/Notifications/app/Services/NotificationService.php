<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * @var Notification
     */
    private $model;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var int
     */
    private $paginate;

    public function __construct(
        Notification $model,
        UserService $userService
    ) {
        $this->model = $model;
        $this->userService = $userService;
        $this->paginate = 25;
    }

    /**
     * All notifications
     *
     * @return  Collection
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Paginated notifications
     *
     * @return  LengthAwarePaginator
     */
    public function paginated()
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($this->paginate);
    }

    /**
     * User based paginated notifications
     *
     * @param  integer $id
     * @return  LengthAwarePaginator
     */
    public function userBasedPaginated($id)
    {
        return $this->model->where('user_id', $id)->orderBy('created_at', 'desc')->paginate($this->paginate);
    }

    /**
     * User based notifications
     *
     * @param  integer $id
     * @return  Notification
     */
    public function userBased($id)
    {
        return $this->model->where('user_id', $id)->where('deleted_at', null)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Search notifications
     *
     * @param  string $input
     * @param  integer $id
     * @return  LengthAwarePaginator
     */
    public function search($input, $id)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$input.'%');

        $columns = Schema::getColumnListing('notifications');

        foreach ($columns as $attribute) {
            if (is_null($id)) {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
            } else {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%')->where('user_id', $id);
            }
        };

        return $query->paginate($this->paginate);
    }

    /**
     * Create a notificaton
     *
     * @param integer $userId
     * @param string $flag
     * @param string $title
     * @param string $details
     * @return  void
     * @throws \Exception
     */
    public function notify($userId, $flag, $title, $details)
    {
        $input = [
            'user_id' => $userId,
            'flag' => $flag,
            'title' => $title,
            'details' => $details,
        ];

        $this->create($input);
    }

    /**
     * Create a notification
     *
     * @param array $input
     * @return Collection
     * @throws \Exception
     */
    public function create($input)
    {
        $notification = collect();
        try {
            if ($input['user_id'] == 0) {
                $users = $this->userService->all();

                foreach ($users as $user) {
                    $input['uuid'] = (string) Str::uuid();
                    $input['user_id'] = $user->id;
                    $notification->push($this->model->create($input));
                }

                $user->notify(new GeneralNotification([
                    'title' => $input['title'],
                    'details' => $input['details'],
                ]));

                return $notification;
            }

            $input['uuid'] = (string) Str::uuid();

            $user = $this->userService->find($input['user_id']);
            $user->notify(new GeneralNotification([
                'title' => $input['title'],
                'details' => $input['details'],
            ]));

            $notification->push($this->model->create($input));
            return $notification;
        } catch (\Exception $e) {
            throw new \Exception("Could not send notifications please try agian.", 1);
        }
    }

    /**
     * Get a user
     *
     * @param integer $id
     * @return User
     */
    public function getUser($id)
    {
        return $this->userService->find($id);
    }

    /**
     * Find a notification
     *
     * @param  integer $id
     * @return  Notification
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a notification by UUID
     *
     * @param  string $uuid
     * @return  Notification
     */
    public function findByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * Update a notification
     *
     * @param  integer $id
     * @param  array $input
     * @return  Notification
     */
    public function update($id, $input)
    {
        $notification = $this->model->find($id);
        $notification->update($input);

        $user = $this->userService->find($notification->user_id);
        $user->notify(new GeneralNotification([
            'title' => $input['title'],
            'details' => $input['details'],
        ]));

        return $notification;
    }

    /**
     * Mark notification as read
     *
     * @param  integer $id
     * @return  boolean
     */
    public function markAsRead($id)
    {
        $input['is_read'] = true;
        return $this->model->find($id)->update($input);
    }

    /**
     * Destroy a Notification
     *
     * @param  integer $id
     * @return  boolean
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Users as Select options array
     *
     * @return  array
     */
    public function usersAsOptions()
    {
        $users = ['All' => 0];

        foreach ($this->userService->all() as $user) {
            $users[$user->name] = $user->id;
        }

        return $users;
    }

}