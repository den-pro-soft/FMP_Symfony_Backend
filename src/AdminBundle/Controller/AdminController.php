<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\AdminType;
use DateTime;
use RestBundle\Entity\Task;
use RestBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminController extends Controller
{
    /**
     * @Route("/admin/admins/add", name="add_admins")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('RestBundle:User');

        if ($request->isMethod('POST')) {
            $admin_bundle   = $request->get('admin_bundle');
            $admin          = new User();

            $full_name      = $admin_bundle['full_name'];
            $email          = $admin_bundle['email'];
            $pass           = $admin_bundle['password']['first'];
            $pass_confirm   = $admin_bundle['password']['second'];
            $role           = $admin_bundle['role'];
            $title          = $admin_bundle['title'];

            $errors = array();
            if($full_name == ''){
                $errors[] = 'Full name is blank.';
            } elseif ($email == ''){
                $errors[] = 'Email is blank';
            } elseif ($pass == ''){
                $errors[] = 'Password is blank';
            } elseif ($pass != $pass_confirm) {
                $errors[] = 'Password mismatch';
            }

            if(count($errors) == 0){
                $admin->setFullName($full_name);
                $admin->setEmail($email);
                $admin->setUsername($email);
                $admin->addRole('ROLE_ADMIN');
                $admin->setPassword($pass);
                $admin->setRole($role);
                $admin->setTitle($title);
                if($admin->getRole() == 'ROLE_ADMIN' || $admin->getRole() == 'ROLE_SDR'){
                    $manager = $userRepo->find($admin_bundle['manager']);
                    $manager->addUser($admin);
                    $admin->addAdmin($manager);
                    $em->persist($admin);
                    $em->persist($manager);
                    $em->flush();
                }

                $plainPassword = $pass;

                $em->persist($admin);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Admin was added successfully'
                );

                $this->get('user.mailer')->sendAdminAccess($admin, $plainPassword);

                return $this->redirectToRoute('view_admin', array('admin' => $admin->getId()));
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        $managers = $userRepo->getAllAdminsByRole('ROLE_ADMIN_MANAGER');
        return $this->render('@Admin/Admin/admin_form.html.twig', array('is_edit' => false, 'managers' => $managers));
    }

    /**
     * @Route("/admin/admins/delete/{admin}", name="delete_admin")
     * @param User $admin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(User $admin)
    {
        if (!$admin->isSuperAdmin()) {
            $em = $this->getDoctrine()->getManager();
            /** @var User[] $users */
            $users = $admin->getUsers();
            /** @var Blog[] $blogs */
            $blogs = $admin->getBlogs();

            if (count($users) > 0) {
                $superAdmin = $em->getRepository('RestBundle:User')->getSuperAdmin();

                foreach ($users as $user) {
                    $user->setAdmin($superAdmin);
                    $em->persist($user);
                }
            }

            if (count($blogs) > 0) {
                $superAdmin = $em->getRepository('RestBundle:User')->getSuperAdmin();

                foreach ($blogs as $blog) {
                    $blog->setAdmin($superAdmin);
                }
            }

            $em->remove($admin);

            $em->flush();

            $this->addFlash(
                'success',
                'Admin was deleted successfully.'
            );
        } else {
            $this->addFlash(
                'error',
                'You can\'t delete a Super Admin.'
            );
        }

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/admin/admins/{admin}", name="view_admin")
     * @param Request $request
     * @param User $admin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $admin)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('RestBundle:User');

        if ($request->isMethod('POST')) {
            $admin_bundle   = $request->get('admin_bundle');
            $full_name      = $admin_bundle['full_name'];
            $email          = $admin_bundle['email'];
            $role           = $admin_bundle['role'];
            $title          = $admin_bundle['title'];

            $errors = array();
            if($full_name == ''){
                $errors[] = 'Full name is blank.';
            } elseif ($email == ''){
                $errors[] = 'Email is blank.';
            }

            if(count($errors) == 0){
                $admin->setFullName($full_name);
                $admin->setEmail($email);
                $admin->setUsername($email);
                $admin->setRole($role);
                $admin->setTitle($title);
                if($admin->getRole() == 'ROLE_ADMIN' || $admin->getRole() == 'ROLE_SDR'){
                    $admins = $admin->getAdmins();
                    foreach($admins as $admin_item){
                        $admin_item->removeUser($admin);
                        $admin->removeAdmin($admin_item);
                        $em->persist($admin);
                        $em->persist($admin_item);
                        $em->flush();
                    }
                    $manager = $userRepo->find($admin_bundle['manager']);
                    $manager->addUser($admin);
                    $admin->addAdmin($manager);
                    $em->persist($admin);
                    $em->persist($manager);
                    $em->flush();
                }
                $em->persist($admin);
                $em->flush();
            } else {
                foreach ($errors as $error){
                    $this->addFlash('error', $error);
                }
            }
        }

        $managers = $userRepo->getAllAdminsByRole('ROLE_ADMIN_MANAGER');
        return $this->render('@Admin/Admin/admin_form.html.twig', array('is_edit' => true, 'managers' => $managers, 'admin' => $admin));
    }

    /**
     * @Route("/admin/tasks/{user}/{filter}", name="list_tasks")
     *
     * @ParamConverter("admin", options={"mapping": {"user" : "id"}})
     * @param User $admin
     * @param $filter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function taskListAction(User $admin, $filter){
        $em             = $this->getDoctrine()->getManager();
        $userRepo       = $em->getRepository('RestBundle:User');
        $task_rep       = $em->getRepository('RestBundle:Task');
        $contact_rep    = $em->getRepository('RestBundle:Contact');
        $completed      = $filter == 'active' ? 'no' : 'yes';
        $today          = new \DateTime();
        $subs           = array();
        if($admin->isSuperAdmin()){
            $subs = $userRepo->getAllAdminsByRole('ROLE_ADMIN');
        } elseif ($admin->isAdminManager()){
            foreach ($admin->getUsers() as $admin_item){
                if($admin_item->isAdmin()) $subs[] = $admin_item;
            }
        } elseif ($admin->isAdmin()){
            $subs[] = $admin;
        }
        $result = array();
        foreach($subs as $admin_item){
            $item               = array();
            $item['admin']      = $admin_item->getFullName();
            $item['clients']    = array();
            $users = $admin_item->getUsers();
            foreach($users as $user){
                $client             = array();
                $client['name']     = $user->getFullName();
                $client['tasks']    = array();

                // delete previous tasks than -30 days
                $all_tasks          = $task_rep->getAllTasksByUser($user);
                foreach($all_tasks as $all_tasks_item){
                    $due_date = $all_tasks_item->getCompleted() == 'yes' ? $all_tasks_item->getCompletedAt() : $all_tasks_item->getDueDate();
                    if(!$due_date) continue;
                    $interval   = date_diff($today, $due_date);
                    $date_diff  = $interval->format('%a');
                    if($date_diff > -31) continue;
                    $em->remove($all_tasks_item);
                }
                $em->flush();

                // add "Happy Birthday" task
                $birthdate = $user->getProfile()->getBirthDate();
                $date_diff = $this->calculateDateDiff($birthdate->format('Y-m-d H:i:s'), $today->format('Y-m-d H:i:s'));
                if($date_diff > -1 && $date_diff < 4){
                    $birth_task = $task_rep->getBirthdayTask($user);
                    if(!$birth_task){
                        $b_task = new Task();
                        $b_task->setUser($user);
                        $b_task->setName('Happy Birthday');
                        $str_date = $date_diff > 1 ? 'now +' . $date_diff . ' days' : 'now +' . $date_diff . ' day';
                        $b_task->setDueDate(new \DateTime($str_date));
                        $em->persist($b_task);
                        $em->flush();
                    }
                }

                // get "Happy Birthday" task
                $birthday_task = $task_rep->getBirthdayTask($user);
                if($birthday_task){
                    if($birthday_task->getCompleted() == $completed){
                        $t_item = array();
                        $t_item['id']           = $birthday_task->getId();
                        $t_item['name']         = $birthday_task->getName();
                        $t_item['completed']    = $birthday_task->getCompleted();

                        $due_date = $completed == 'yes' ? $birthday_task->getCompletedAt() : $birthday_task->getDueDate();
                        if(!$due_date){
                            $t_item['due_date']     = '';
                            $t_item['due_class']    = '';
                        } else {
                            $t_item['due_date']     = date_format($due_date, 'Y-m-d');
                            $t_item['due_class']    = '';
                            if($completed == 'no'){
                                $date_diff = $this->calculateDateDiff($due_date->format('Y-m-d H:i:s'), $today->format('Y-m-d H:i:s'));
                                if ($date_diff > 1) {
                                    $t_item['due_date'] .= ' (' . $date_diff . ' days)';
                                    $t_item['due_class'] .= ' due-date-green';
                                } elseif ($date_diff == 1) {
                                    $t_item['due_date'] .= ' (' . $date_diff . ' day)';
                                    $t_item['due_class'] .= ' due-date-yellow';
                                } elseif ($date_diff == 0) {
                                    $t_item['due_date'] .= ' (today)';
                                    $t_item['due_class'] .= ' due-date-red';
                                } else {
                                    $t_item['due_date'] .= ' (' . $date_diff . ' days)';
                                    $t_item['due_class'] .= ' due-date-red';
                                }
                            }
                        }

                        $t_item['contact_name']     = '';
                        $t_item['contact_link']     = '';
                        $t_item['contact_title']    = 'Wish Client A Happy Birthday!';
                        $client['tasks'][]          = $t_item;
                    }
                }

                $jobs = $user->getJobs()->toArray();
                foreach($jobs as $job){
                    $tasks = $task_rep->getAllTasksByJob($job);
                    foreach($tasks as $task){
                        if ($task->getCompleted() != $completed) continue;
                        $t_item = array();
                        $t_item['id']           = $task->getId();
                        $t_item['name']         = $task->getName();
                        $t_item['completed']    = $task->getCompleted();

                        $due_date = $completed == 'yes' ? $task->getCompletedAt() : $task->getDueDate();

                        if(!$due_date){
                            $t_item['due_date']     = '';
                            $t_item['due_class']    = '';
                        } else {
                            $t_item['due_date'] = date_format($due_date, 'Y-m-d');
                            $t_item['due_class'] = '';
                            if($completed == 'no'){
                                $date_diff = $this->calculateDateDiff($due_date->format('Y-m-d H:i:s'), $today->format('Y-m-d H:i:s'));
                                if ($date_diff > 1) {
                                    $t_item['due_date'] .= ' (' . $date_diff . ' days)';
                                    $t_item['due_class'] .= ' due-date-green';
                                } elseif ($date_diff == 1) {
                                    $t_item['due_date'] .= ' (' . $date_diff . ' day)';
                                    $t_item['due_class'] .= ' due-date-yellow';
                                } elseif ($date_diff == 0) {
                                    $t_item['due_date'] .= ' (today)';
                                    $t_item['due_class'] .= ' due-date-red';
                                } else {
                                    $t_item['due_date'] .= ' (' . $date_diff . ' days)';
                                    $t_item['due_class'] .= ' due-date-red';
                                }
                            }
                        }

                        $t_item['contact_name']     = $job->getPosition();
                        $t_item['contact_link']     = $job->getLink();
                        $t_item['contact_title']    = $job->getCompany();
                        if($task->getContactId() > 0){
                            $contact = $contact_rep->find($task->getContactId());
                            $t_item['contact_name']     = $contact->getName();
                            $t_item['contact_link']     = $contact->getLink();
                            $t_item['contact_title']    = $contact->getTitle();
                        }
                        $client['tasks'][] = $t_item;
                    }
                }
                $item['clients'][] = $client;
            }
            $result[] = $item;
        }

        return $this->render('@Admin/Admin/tasks.html.twig', array(
            'filter_val'    => $filter,
            'tasks'         => $result
        ));
    }

    public function calculateDateDiff($first, $second){
        $firstdate  = date('d-m-2000',strtotime($first));
        $seconddate = date('d-m-2000',strtotime($second));
        $datediff   = strtotime($firstdate) - strtotime($seconddate);
        $totalDays  = floor($datediff / (60 * 60 * 24));
        if($datediff > $totalDays * 60 * 60 * 24) $totalDays++;
        return $totalDays;
    }
}
