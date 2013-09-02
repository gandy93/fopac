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

    public function createAction(Request $request)
    {
    	if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
    		$title = $request->get('title');   $description = $request->get('description'); 
    		$passphrase = $request->get('passphrase');

    		$room = new Room();
    		if (empty($title)) { $room->setTitle(md5(date("U"))); } else { $room->setTitle($title); }
    		$room->setDescription(htmlspecialchars($description));
    		$room->setPassphrase($passphrase);

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

    public function editAction(Request $r)
    {
    	if ($r->isXmlHttpRequest() && $r->getMethod() == "POST") {
    		$roomData = $r->get('room') ;  $auth = $r->get('auth');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));

    		if ($user) {
    			$room = $user->getRoom();
    			if ($room->getId() == $roomData['id'] && $user->isModerator() && $room->getPassphrase() == $roomData['pass']) { 
    				if (empty($roomData['title'])) { $room->setTitle(md5(date("U"))); } else { $room->setTitle($roomData['title']); }
    				$room->setDescription(htmlspecialchars($roomData['desc']));
    				$room->setPassphrase($roomData['newPass']);

    				$em = $this->getDoctrine()->getManager();
    				$em->flush();

    				$res = new JsonResponse();
    				$res->setData(array('Status' => 0));
    				return $res;
    			}
    		}
    	}

    	$res = new JsonResponse();
    	$res->setData(array('Status' => 1));
    	return $res;
    }

    public function passCheckAction(Request $r)
    {    	
    	if ($r->isXmlHttpRequest() && $r->getMethod() == "POST") {
    		$roomId = $r->get('roomId');  $passphrase = $r->get('passphrase');

    		$room = $this->getDoctrine()->getRepository('FOPACChatBundle:Room')->find($roomId);
    		if ($room && $room->getPassphrase() == $passphrase) { $param = array('Status' => 0); }
    		else { $param = array('Status' => 1); }

    		$response = new JsonResponse();
    		$response->setData($param);
    		return $response;
    	}   	
    }

    public function destroyAction(Request $r)
    {    	
    	if ($r->isXmlHttpRequest() && $r->getMethod() == "POST") {
    		$roomId = $r->get('roomId');  $passphrase = $r->get('passphrase');  $auth = $r->get('auth');

    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));
    		if ($user) {
    			$room = $user->getRoom();
    			if ($room->getId() == $roomId && $user->isModerator() && $room->getPassphrase() == $passphrase) { 
    				$em = $this->getDoctrine()->getManager();
    				$em->remove($room); 
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

    public function infoAction(Request $r)
    {    	
    	if ($r->isXmlHttpRequest()) {
    		$roomId = $r->get('roomId');  $passphrase = $r->get('passphrase');  $auth = $r->get('auth');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));
    		if ($user) {
    			$room = $user->getRoom();
    			if ($room->getId() == $roomId && $room->getPassphrase() == $passphrase) { 
    				$user->setLastAccessAt(new \DateTime());
    				$room->setLastAccessAt($user->getLastAccessAt());
    				$this->getDoctrine()->getManager()->flush();

    				$permits = $room->getPermits();
    				$perm = array();
    				foreach ($permits as $i => $p) {
    					$perm[$i] = array('nick' => substr($p->getAuthHash(), 0, 10),
    									  'isModerator' => $p->isModerator());

						$access = $p->getLastAccessAt();
    					if ($access && (time() - $access->getTimestamp()) <= 60*2.5) { $perm[$i]['Online'] = true; }
    					if ($user->isModerator()) {
    						$perm[$i]['id'] = $p->getId();
    						$perm[$i]['authHash'] = $p->getAuthHash();
    						$perm[$i]['authURL'] = $this->get('router')->generate('chat_page', array(
    																			  'permitId' => $p->getId(),
    																			  'auth' => $p->getAuthHash()), true);
    					}
    				}

    				$res = new JsonResponse();
    				$res->setData(array('Status' => 0,
    									'Room' => array('id' => $roomId,
    													'title' => $room->getTitle(),
    													'description' => $room->getDescription()),
    									'Permits' => $perm));
    				
    				return $res;
    			}
    		}
    		
    		$res = new JsonResponse();
    		$res->setData(array('Status' => 1));
    		return $res;
    	}   	
    }

    public function addPermitAction(Request $r)
    {
    	if ($r->isXmlHttpRequest() && $r->getMethod() == "POST") {
    		$pass = $r->get('pass') ;  $auth = $r->get('auth');  $mod = $r->get('mod');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));

    		if ($user) {
    			$room = $user->getRoom();
    			if ($user->isModerator() && $room->getPassphrase() == $pass) { 
    				$p = new Permit();
    				$p->setRoom($room);
    				$p->setAuthHash(hash('sha256', sprintf("%d|%s|%d|%d", $room->getId(), $room->getTitle(),
    			time(), rand(0, 10000))));
    				if ($mod == 'true') { $p->setModerator(true); } else { $p->setModerator(false); }    				

    				$em = $this->getDoctrine()->getManager();
    				$em->persist($p);
    				$em->flush();

    				$res = new JsonResponse();
    				$res->setData(array('Status' => 0,
    									'permitId' => $p->getId(),
    									'authHash' => $p->getAuthHash(),
    									'authURL' => $this->get('router')->generate('chat_page', array(
    																			    'permitId' => $p->getId(),
    																			    'auth' => $p->getAuthHash()), true),
    									'mod' => $p->isModerator()));
    				return $res;
    			}
    		}
    	}

    	$res = new JsonResponse();
    	$res->setData(array('Status' => 1));
    	return $res;
    }

    public function removePermitAction(Request $r)
    {
    	if ($r->isXmlHttpRequest() && $r->getMethod() == "POST") {
    		$pass = $r->get('pass') ;  $auth = $r->get('auth');  $permitId = $r->get('id');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));

    		if ($user) {
    			$room = $user->getRoom();
    			if ($user->isModerator() && $room->getPassphrase() == $pass) { 
    				$p = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->find($permitId);
    				if ($p)	{
    					$em = $this->getDoctrine()->getManager();
    					$em->remove($p);
    					$em->flush();

    					$res = new JsonResponse();
    					$res->setData(array('Status' => 0));
    					return $res;
    				}    				
    			}
    		}
    	}

    	$res = new JsonResponse();
    	$res->setData(array('Status' => 1));
    	return $res;
    }

    public function changePermitAction(Request $r)
    {
    	if ($r->isXmlHttpRequest() && $r->getMethod() == "POST") {
    		$pass = $r->get('pass') ;  $auth = $r->get('auth');  $permitId = $r->get('id');  $mod = $r->get('mod');
    		$user = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->findOneBy(array('authHash' => $auth));

    		if ($user) {
    			$room = $user->getRoom();
    			if ($user->isModerator() && $room->getPassphrase() == $pass) { 
    				$p = $this->getDoctrine()->getRepository('FOPACChatBundle:Permit')->find($permitId);
    				if ($p)	{
    					if ($mod == 'true') { $p->setModerator(true); } else { $p->setModerator(false); }
    					$em = $this->getDoctrine()->getManager();    					
    					$em->flush();

    					$res = new JsonResponse();
    					$res->setData(array('Status' => 0));
    					return $res;
    				}    				
    			}
    		}
    	}

    	$res = new JsonResponse();
    	$res->setData(array('Status' => 1));
    	return $res;
    }

    public function cleanAction($secret)
    {
    	if ($secret == $this->container->getParameter('clean_secret')) {
    		$date = new \DateTime(); $date = $date->sub(new \DateInterval('P7D'));
    		$em = $this->getDoctrine()->getManager();
    		$rooms = $em->createQuery('SELECT r FROM FOPACChatBundle:Room r WHERE (r.lastAccessAt < :date OR r.lastAccessAt IS NULL)')
    		->setParameter('date', $date)->getResult();

            foreach ($rooms as $i => $r) { $em->remove($r); }
            $em->flush();

            return new JsonResponse(array('Status' => 0));
    	}

    	return new JsonResponse(array('Status' => 1));
    }    
}