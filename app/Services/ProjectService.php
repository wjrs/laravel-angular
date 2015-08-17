<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectService
{

    protected $repository;
    /**
     * @var ProjectValidator
     */
    private $validator;


    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);

        } catch(ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);

        } catch(ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function addMember(array $data, $id)
    {
        try {
            $project = $this->repository->find($id);
            $project->members()->attach($data);

        } catch(ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function removeMember(array $data, $id)
    {
        try {
            $project = $this->repository->find($id);
            $project->members()->detach($data);

        } catch(ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function isMember($id, $memberId)
    {
        try {
            $project = $this->repository->with('members')->find($id);
            $member  = $project->members()->find($memberId);

            if (!empty($member)) {
                return 'true';
            } else {
                return 'false';
            }

        } catch(ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }


    }
}
