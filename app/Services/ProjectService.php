<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Contracts\Validation\ValidationException;


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

        } catch(ValidationException $e) {
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

        } catch(ValidationException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }
}