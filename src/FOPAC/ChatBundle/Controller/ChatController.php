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

	public function newMessagesAction(Request $r)
	{
		if ($r->isXmlHttpRequest()) {
    		$last = $r->get('last');  $passphrase = $r->get('passphrase');  $auth = $r->get('auth');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));
    		if ($user) {
    			$room = $user->getRoom();
    			if ($room->getPassphrase() == $passphrase) { 
    				if ($last < 0) {
    					$msg = $this->getDoctrine()->getRepository('FOPACChatBundle:Message')->findby(array('room' => $room), array('id' => 'desc'), 20);
    					$msg = array_reverse($msg);
    				} else {
    					$msg = $this->getDoctrine()->getRepository('FOPACChatBundle:Message')->findAllNewest($last, $room);
    				}
    				
    				$results = array();
    				foreach ($msg as $i => $m) {
    					$results[$i] = array('id' => $m->getId(),
    									'nick' => $m->getNickname(),
    									'text' => $m->getContent(),
    									'time' => $m->getCreatedAt()->format('c'));
    				}

    				$res = new JsonResponse();
    				$res->setData(array('Status' => 0, 'msg' => $results));    				
    				return $res;
    			}
    		}
    		
    		$res = new JsonResponse();
    		$res->setData(array('Status' => 1));
    		return $res;
    	}
	}

	public function oldMessagesAction(Request $r)
	{
		if ($r->isXmlHttpRequest()) {
    		$first = $r->get('first');  $passphrase = $r->get('passphrase');  $auth = $r->get('auth');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));
    		if ($user) {
    			$room = $user->getRoom();
    			if ($room->getPassphrase() == $passphrase) { 
    				$msg = $this->getDoctrine()->getRepository('FOPACChatBundle:Message')->findOlder($first, $room, 5);

    				$results = array();
    				foreach ($msg as $i => $m) {
    					$results[$i] = array('id' => $m->getId(),
    									'nick' => $m->getNickname(),
    									'text' => $m->getContent(),
    									'time' => $m->getCreatedAt()->format('c'));
    				}

    				$res = new JsonResponse();
    				$res->setData(array('Status' => 0, 'msg' => $results));    				
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