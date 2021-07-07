<?php

namespace App\Security\Voter;

use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class OfferVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CAN_EDIT', 'CAN_DELETE', 'CAN_SHOW'])
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
        $id = $subject->getBrandId()->getUser();

        switch ($attribute) {
            case 'CAN_EDIT':
            case 'CAN_DELETE':
                return $id  === $user;
                break;
            case 'CAN_SHOW':
                return $id  === $user || $user->getRoles() === ["ROLE_INFLUENCEUR"];
                break;
        }

        return false;
    }
}
