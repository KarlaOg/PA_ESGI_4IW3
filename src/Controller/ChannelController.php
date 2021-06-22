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
     * @Route("/chan", name="channel_home")
     */
    public function getChannels(ChannelRepository $channelRepository): Response
    {
        $channels = $channelRepository->findAll();

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
        $user1 = $this->getUser();
        $user2 = $user;

        $channel = new Channel();
        if($user2->getRoles[0] = "ROLE_MARQUE" ){
            $name2 = $brandRepository->find($user2->getId());//->getName();
            var_dump($user2->getId());die();
        }else{
            $name2 = $influencerRepository->find($user2->getId())->getName();
        }
        $channel->setName('hey you');
        $channel->setUser1($user1);
        $channel->setUser2($user2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($channel);
        $em->flush();

        return $this->redirectToRoute('channel_chat', ['id' => $channel->getId()]);
        //$channels = $channelRepository->findAll();

        /*return $this->render('channel/index.html.twig', [
            'channels' => $channels ?? []
        ]);*/
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
