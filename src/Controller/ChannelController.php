<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\User;
use App\Repository\ChannelRepository;
use App\Repository\InfluencerRepository;
use App\Repository\BrandRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

class ChannelController extends AbstractController
{
    /**
     * @Route("/chan/{id}", name="channel_home")
     */
    public function getChannels(User $user, ChannelRepository $channelRepository): Response
    {
        if($this->getUser() != $user){
            return $this->render('channel/index.html.twig', [
                'channels' =>  "Security"
            ]);
        }

        $channels1 = $channelRepository->findBy([
            'user1' => $user
        ]);
        $channels2 = $channelRepository->findBy([
            'user2' => $user
        ]);
        $channels = array_merge($channels1, $channels2);
        
        return $this->render('channel/index.html.twig', [
            'channels' => $channels ?? []
        ]);
    }

    /**
     * @Route("/chancreate2/{id}", name="channel_create2")
     */
    public function createChannel2(User $user, ChannelRepository $channelRepository, BrandRepository $brandRepository, InfluencerRepository $influencerRepository ): Response
    {
        $user1 = $this->getUser();
        $user2 = $user;
        
        $findChannel = $channelRepository->findBy([
            'user1' => $user1,
            'user2' => $user2
        ]);
        if($findChannel != null){
            return $this->redirectToRoute('channel_chat', ['id' => $findChannel[0]->getId()]);
        }else{
            $findChannel = $channelRepository->findBy([
                'user1' => $user2,
                'user2' => $user1
            ]);
            if($findChannel != null)
                return $this->redirectToRoute('channel_chat', ['id' => $findChannel[0]->getId()]);
        }
        
        $channel = new Channel();
        if($user1->getRoles()[0] == "ROLE_MARQUE" ){
            $name1 = $brandRepository->findBy(['user' => $user1->getId()])[0]->getName();
            $name2 = $influencerRepository->findBy(['user' => $user2->getId()])[0]->getName();
        }
        elseif ($user1->getRoles()[0] == "ROLE_INFLUENCEUR" ) {
            $name1 = $influencerRepository->findBy(['user' => $user1->getId()])[0]->getName();
            $name2 = $brandRepository->findBy(['user' => $user2->getId()])[0]->getName();
        }

        $channel->setName($name1.' - '.$name2);
        $channel->setUser1($user1);
        $channel->setUser2($user2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($channel);
        $em->flush();

        return $this->redirectToRoute('channel_chat', ['id' => $channel->getId()]);
        
    }

    /**
     * @Route("/chat/{id}", name="channel_chat")
     */
    public function chat(Channel $channel, MessageRepository $messageRepository, ChannelRepository $channelRepository): Response 
    {
        $user = $this->getUser();
        $chennelId = $channel->getId();

        $findChannel1 = $channelRepository->findBy([
            'id' => $chennelId,
            'user1' => $user
        ]);

        $findChannel2 = $channelRepository->findBy([
            'id' => $chennelId,
            'user2' => $user
        ]);
        
        if ($findChannel1 == null && $findChannel2 == null) {
            return $this->render('channel/chat.html.twig', [
                'autorisation' => "refus",
                'channel' => $channel,
                'messages' => ""
            ]);
        }
        $messages = $messageRepository->findBy([
            'channel' => $channel
        ], ['createdAt' => 'ASC']);

        return $this->render('channel/chat.html.twig', [
            'autorisation' => "acces",
            'channel' => $channel,
            'messages' => $messages
        ]);
    }
}
