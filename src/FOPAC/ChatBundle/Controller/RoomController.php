<?php

namespace FOPAC\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOPAC\ChatBundle\Entity\Room;
use FOPAC\ChatBundle\Entity\Permit;

class RoomController extends Controller
{
    /*public function indexAction($name)
    {
        return $this->render('FOPACChatBundle:Default:index.html.twig', array('name' => $name));
    }*/

    public function createAction(Request $request, $title, $description, $passphrase)
    {
    	if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
    		$room = new Room();
    		if (empty($title)) { $room->setTitle(md5(date("U"))); }
    		$room->setDescription = htmlspecialchars($description);
    		$room->setPassphrase = $passphrase;

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($room);
    		$em->flush();

    		$owner = new Permit();
    		$owner->setRoom($room);
    		$owner->setAuthHash(hash('sha256', sprintf("%d|%s|%d|%d", $room->getId(), $room->getTitle(),
    			time(), rand(0, 10000))));
    		$owner->setModerator(true);
    		$em->persist($owner);
    		$em->flush();

    		$response = new JsonResponse();
    		$response->setData(array(
    			'Status' => 0, 
    			'AuthURL' => $this->get('router')->generate('chat_page', array(
    				'permitId' => $owner->getId(),
    				'auth' => $owner->getAuthHash()), true)));
    		return $response;
    	}
    }
}
