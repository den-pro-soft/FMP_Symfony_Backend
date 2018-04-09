<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class IndexController
 * @package AdminBundle\Controller
 */
class IndexController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function indexAction()
    {
        $admins = $this->getDoctrine()->getManager()->getRepository('RestBundle:User')->getAdmins();
        $admin = $this->getUser();

        if ($admin->IsSuperAdmin() || $admin->getRole() === 'ROLE_ADMIN_MANAGER') {
            return $this->render('@Admin/Admin/admins.html.twig', array('admins' => $admins));
        }

        if ($admin->getRole() === 'ROLE_MANAGER_BLOG') {
            return $this->redirectToRoute('view_list_blog');
        }

        $users = $admin->getUsers();

        return $this->render('@Admin/Admin/users.html.twig', array(
            'users'         => $users,
            'filter_val'    => "",
            'admins'        => $admins
        ));
    }
}
