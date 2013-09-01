<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        // _welcome
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_welcome');
            }

            return array (  '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController::templateAction',  'template' => 'FOPACChatBundle:Static:index.html.twig',  '_route' => '_welcome',);
        }

        // fopac_chat_homepage
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?P<name>[^/]++)$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'fopac_chat_homepage')), array (  '_controller' => 'FOPACChatBundle:Default:index',));
        }

        // home_page
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'home_page');
            }

            return array (  '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController::templateAction',  'template' => 'FOPACChatBundle:Static:index.html.twig',  '_route' => 'home_page',);
        }

        // chat_page
        if (0 === strpos($pathinfo, '/chat') && preg_match('#^/chat/(?P<permitId>[^/]++)/(?P<auth>[^/]++)$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'chat_page')), array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\ChatController::indexAction',));
        }

        // dll_chat
        if (0 === strpos($pathinfo, '/download') && preg_match('#^/download/(?P<permitId>[^/]++)/(?P<auth>[^/]++)(?:/(?P<pass>[^/]++))?$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dll_chat')), array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\ChatController::dllAction',  'pass' => '',));
        }

        if (0 === strpos($pathinfo, '/ajax')) {
            if (0 === strpos($pathinfo, '/ajax/room')) {
                // create_room
                if ($pathinfo === '/ajax/room/new') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_create_room;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::createAction',  '_route' => 'create_room',);
                }
                not_create_room:

                // check_pass
                if ($pathinfo === '/ajax/room/verify-pass') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_check_pass;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::passCheckAction',  '_route' => 'check_pass',);
                }
                not_check_pass:

                // destroy_room
                if ($pathinfo === '/ajax/room/destroy') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_destroy_room;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::destroyAction',  '_route' => 'destroy_room',);
                }
                not_destroy_room:

                // sidebar_info
                if ($pathinfo === '/ajax/room/info') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_sidebar_info;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::infoAction',  '_route' => 'sidebar_info',);
                }
                not_sidebar_info:

                // edit_room
                if ($pathinfo === '/ajax/room/edit') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_edit_room;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::editAction',  '_route' => 'edit_room',);
                }
                not_edit_room:

            }

            if (0 === strpos($pathinfo, '/ajax/permit')) {
                // add_permit
                if ($pathinfo === '/ajax/permit/add') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_add_permit;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::addPermitAction',  '_route' => 'add_permit',);
                }
                not_add_permit:

                // remove_permit
                if ($pathinfo === '/ajax/permit/remove') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_remove_permit;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::removePermitAction',  '_route' => 'remove_permit',);
                }
                not_remove_permit:

                // change_permit
                if ($pathinfo === '/ajax/permit/change') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_change_permit;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\RoomController::changePermitAction',  '_route' => 'change_permit',);
                }
                not_change_permit:

            }

            if (0 === strpos($pathinfo, '/ajax/chat')) {
                // send_msg
                if ($pathinfo === '/ajax/chat/send') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_send_msg;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\ChatController::sendAction',  '_route' => 'send_msg',);
                }
                not_send_msg:

                // new_msg
                if ($pathinfo === '/ajax/chat/new') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_new_msg;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\ChatController::newMessagesAction',  '_route' => 'new_msg',);
                }
                not_new_msg:

                // old_msg
                if ($pathinfo === '/ajax/chat/old') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_old_msg;
                    }

                    return array (  '_controller' => 'FOPAC\\ChatBundle\\Controller\\ChatController::oldMessagesAction',  '_route' => 'old_msg',);
                }
                not_old_msg:

            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
