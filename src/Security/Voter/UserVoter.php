<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

// tuto https://www.youtube.com/watch?v=wSh9zlL2xzc

class UserVoter extends Voter
{
    const CREATE_USER = 'create_user';
    const DELETE_USER = 'delete_user';
    const EDIT_USER = 'edit_user';
    const VIEW_USER = 'view_user';
    // browsing authorisation
    const BROWSER_USER = 'browser_user';
    // user authorisation on task controller
    const CREATE_TASK = 'create_task';

    private $security;
    

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->tokenUser = $this->security->getUser();
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE_USER,self::DELETE_USER,self::EDIT_USER,self::VIEW_USER,self::BROWSER_USER,self::CREATE_TASK])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
  
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE_TASK:
                return $this->createTask();
                break;
            case self::BROWSER_USER:
                return $this->browserUser();
                break;
            case self::CREATE_USER:
                // logic to determine if the user can CREATE an user
                return $this->createUser();
                break;
            case self::EDIT_USER:
                    // logic to determine if the user can CREATE an user
                    return $this->editUser();
                    break;
            case self::DELETE_USER:
                // logic to determine if the user can DELETE an user
                return $this->deleteUser();
                break;
            case self::VIEW_USER:
                 // logic to determine if the user can View the users details
                return $this->viewUser();
                break;
        }

        return false;
    }

    // task Controller
    private function createTask()
    {
         // by default each member verified get the role User
         if (!$this->tokenUser->isVerified()) {
            return false;
         }
 
         if ($this->security->isGranted('ROLE_USER')) {
             return true;
         }
       
        return false;   
    }

    private function browserUser()
    {
        // by default each member verified get the role User
        if (!$this->tokenUser->isVerified()) {
           return false;
        }

        if ($this->security->isGranted('ROLE_USER')) {
            return true;
        }
       
        return false;
    }

    // user Controller
    // for see the users details
    private function viewUser()
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
         }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
       
        return false;
    }

    private function editUser()
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
         }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
       
        return false;
    }

    private function createUser()
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
         }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    private function deleteUser()
    {
        if (!$this->tokenUser->isVerified()) {
            return false;
         }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}
