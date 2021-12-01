<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    const VIEW_TASK = 'view_task';
    const EDIT_TASK = 'edit_task';
    const NEW_TASK ='new_task';
    const DELETE_TASK = 'delete_task';
    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $task): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW_TASK,self::EDIT_TASK,self::NEW_TASK,self::DELETE_TASK])
            && $task instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, $task, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW_TASK:
                // logic to determine if the user can View the task
                return $this->viewTask();
                break;
            case self::NEW_TASK:
                // logic to determine if the user can VIEW
                return $this->newTask();
                break;
            case self::EDIT_TASK:
                return $this->editTask($task);
                break;
            case self::DELETE_TASK:
                return $this->deleteTask($task);
                break;
        }

        return false;
    }

    private function viewTask()
    {
        if ($this->security->isGranted('ROLE_USER')) {
            return true;
        }
       
        return false;
    }

    private function newTask()
    {
        if ($this->security->isGranted('ROLE_USER')) {
            return true;
        }
       
        return false;
    }

    private function editTask($task)
    {
        if ($task->getUser() === $this->security->getUser() ) {
           return true;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
       
        return false;
    }

    private function deleteTask($task)
    {
        if ($task->getUser() === $this->security->getUser() ) {
            return true;
         }
 
         if ($this->security->isGranted('ROLE_ADMIN')) {
             return true;
         }
        
         return false;
    }
}
