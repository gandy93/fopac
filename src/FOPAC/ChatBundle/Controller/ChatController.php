<?php 
namespace FOPAC\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOPAC\ChatBundle\Entity\Room;
use FOPAC\ChatBundle\Entity\Permit;
use FOPAC\ChatBundle\Entity\Message;


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

	public function sendAction(Request $r)
	{
		if ($r->isXmlHttpRequest()) {
    		$msg = $r->get('msg');  $passphrase = $r->get('passphrase');  $auth = $r->get('auth');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));
    		if ($user && $msg) {
    			$room = $user->getRoom();
    			if ($room->getPassphrase() == $passphrase) { 
    				$m = new Message();
    				$m->setNickname(substr($user->getAuthHash(), 0, 10));
    				$m->setContent(htmlspecialchars($msg));
    				$m->setRoom($room);

    				$em = $this->getDoctrine()->getManager();
    				$em->persist($m);
    				$em->flush();

    				$res = new JsonResponse();
    				$res->setData(array('Status' => 0));    				
    				return $res;
    			}
    		}
    		
    		$res = new JsonResponse();
    		$res->setData(array('Status' => 1));
    		return $res;
    	}
	}
}
?>