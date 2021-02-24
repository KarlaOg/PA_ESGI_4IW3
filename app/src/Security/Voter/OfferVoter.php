<?php

namespace App\Security\Voter;

use App\Repository\BrandRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OfferVoter extends Voter
{
    protected $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CAN_EDIT', 'CAN_DELETE'])
            && $subject instanceof \App\Entity\Offer;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //(check conditions and return true to grant permission) 
        $id = $subject->getBrandId()->getUserId();

        switch ($attribute) {
            case 'CAN_EDIT':
                return $id  === $user;
                break;
            case 'CAN_DELETE':
                return $id  === $user;
                break;
        }

        return false;
    }
}
