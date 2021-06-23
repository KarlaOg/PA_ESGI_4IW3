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

        $channels = $channelRepository->findBy([
            'user1' => $user
        ]);
        if ($channels == null){
            $channels = $channelRepository->findBy([
                'user2' => $user
            ]);
        }
        return $this->render('channel/index.html.twig', [
            'channels' => $channels ?? []
        ]);
    }

    /**
     * @Route("/chancreate", name="channel_create")
     */
    public function createChannel(ChannelRepository $channelRepository): Response
    {
        $channel = new Channel();
        $channel->setName('test');

        $em = $this->getDoctrine()->getManager();
        $em->persist($channel);
        $em->flush();

        $channels = $channelRepository->findAll();

        return $this->render('channel/index.html.twig', [
            'channels' => $channels ?? []
        ]);
    }

    /**
     * @Route("/chancreate2/{id}", name="channel_create2")
     */
    public function createChannel2(User $user, ChannelRepository $channelRepository, BrandRepository $brandRepository, InfluencerRepository $influencerRepository ): Response
    {
        // TODO dans le cas ou le channel existe déja, il faut redirect sans creer de nouveau
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

        /*$findChannel = $channelRepository->findBy([
            'user1' => $user1,
            'user2' => $user2
        ]);
        die();*/
        $channel = new Channel();
        if($user1->getRoles()[0] == "ROLE_MARQUE" ){
            //var_dump("clique sur une marque");die();
            $name1 = $brandRepository->findBy(['user' => $user1->getId()])[0]->getName();
            $name2 = $influencerRepository->findBy(['user' => $user2->getId()])[0]->getName();
        }elseif ($user1->getRoles()[0] == "ROLE_INFLUENCEUR" ) {
            //var_dump("clique sur influ");die();
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
    public function chat(
        Channel $channel,
        MessageRepository $messageRepository
    ): Response {
        $messages = $messageRepository->findBy([
            'channel' => $channel
        ], ['createdAt' => 'ASC']);

        return $this->render('channel/chat.html.twig', [
            'channel' => $channel,
            'messages' => $messages
        ]);
    }
}
