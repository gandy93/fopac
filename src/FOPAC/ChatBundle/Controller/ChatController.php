<?php 
namespace FOPAC\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOPAC\ChatBundle\Entity\Room;
use FOPAC\ChatBundle\Entity\Permit;


class ChatController extends Controller
{
	
	function indexAction($permitId, $auth)
	{
		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->find($permitId);
		if (!$user || $user->getAuthHash() != $auth) {
			$param = array('Status' => 1);
		} else {
			$room = $user->getRoom();
			if ($room->getPassphrase()) {
				$param = array('Status' => 0, 
							   'User' => array('PermitID' => $permitId,
							   				   'AuthHash' => $auth,
							   				   'IsModerator' => $user->isModerator()),
							   'RoomID' => $room->getId(), 
							   'HasPassphrase' => true);
			} else {
				$param = array('Status' => 0, 
							   'User' => array('PermitID' => $permitId,
							   				   'AuthHash' => $auth,
							   				   'IsModerator' => $user->isModerator()),							   
							   'RoomID' => $room->getId(), 
							   'HasPassphrase' => false);
			}
		}

		return $this->render('FOPACChatBundle:Chat:index.html.twig', $param);
	}
}
?>