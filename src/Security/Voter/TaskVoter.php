<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    // view task details
    const VIEW_TASK = 'view_task';
    // task edit
    const EDIT_TASK = 'edit_task';
    // delete task
    const DELETE_TASK = 'delete_task';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->tokenUser = $this->security->getUser();
    }

    protected function supports(string $attribute, $task): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW_TASK,self::EDIT_TASK,self::DELETE_TASK])
            && $task instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        // @codeCoverageIgnoreStart
        if (!$user instanceof UserInterface) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW_TASK:
                // logic to determine if the user can View the task
                return $this->viewTask();
                break;
            case self::EDIT_TASK:
                return $this->editTask($task);
                break;
            case self::DELETE_TASK:
                return $this->deleteTask($task);
                break;
        }

        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }

    private function viewTask()
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
        }

        return true;
    }

    private function editTask($task)
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
        }

        if ($task->getUser() === $this->security->getUser()) {
            return true;
        }

        if ($this->security->isGranted('ROLE_ADMIN') && $task->getUser()->getUserIdentifier() === "anonyme@gmail.com") {
            return true;
        }

        return false;
    }

    private function deleteTask($task)
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
        }

        if ($task->getUser() === $this->security->getUser()) {
            return true;
        }

        if ($this->security->isGranted('ROLE_ADMIN') && $task->getUser()->getUserIdentifier() === "anonyme@gmail.com") {
            return true;
        }

         return false;
    }
}
