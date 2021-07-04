<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Repository\ChannelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message_sendMessage", methods={"POST"})
     */
    public function sendMessage(
        Request $request,
        ChannelRepository $channelRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        NotifierInterface $notifier
    ): JsonResponse {

        $data = \json_decode($request->getContent(), true); // On récupère les data postées et on les déserialize
        if (empty($content = $data['content'])) {
            throw new AccessDeniedHttpException('No data sent');
        }

        $channel = $channelRepository->findOneBy([
            'id' => $data['channel'] // On cherche à savoir de quel channel provient le message
        ]);
        if (!$channel) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific channel');
        }

        $user = $this->getUser();

        $message = new Message(); // Après validation, on crée le nouveau message
        $message->setContent($content);
        $message->setChannel($channel);
        $message->setAuthor($user); // On lui attribue comme auteur l'utilisateur courant

        $userEmail_send_msg = $user->getEmail();

        if ($user == $channel->getUser1()) {
            $user_received_msg = $channel->getUser2();
        } else {
            $user_received_msg = $channel->getUser1();
        }

        $userEmail_received_msg = $user_received_msg->getEmail();

        $notification = (new Notification('Vous avez reçu un nouveau message'))
            ->content('Bonjour ' . $user_received_msg->getFirstname() . $user_received_msg->getLastname() . ', vous venez de recevoir un nouveau message de ' . $user->getFirstname() . $user->getLastname());

        // utilisateur qui envoi le message recoit une confirmation par mail
        $recipient = new Recipient(
            $userEmail_received_msg
        );

        $notifier->send($notification, $recipient);


        $notification = (new Notification('Confirmation d\'envoi du message'))
            ->content('Bonjour ' . $user->getFirstname() . $user->getLastname() . ', votre message a bien été envoyé à ' . $user_received_msg->getFirstname() . $user_received_msg->getLastname());

        // utilisateur qui recoit le message, recoit une notification par mail
        $recipient = new Recipient(
            $userEmail_send_msg
        );

        $notifier->send($notification, $recipient);

        $em->persist($message);
        $em->flush(); // Sauvegarde du nouvel objet en DB

        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message'] // On serialize la réponse avant de la renvoyer
        ]);

        return new JsonResponse( // Enfin, on retourne la réponse
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
