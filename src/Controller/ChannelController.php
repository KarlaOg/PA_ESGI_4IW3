<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
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
