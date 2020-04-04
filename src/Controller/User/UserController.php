<?php

namespace App\Controller\User;

use App\Form\UserFormType;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{
    /** @var UserService */
    private $service;

    public function __construct(
        UserService $service
    ) {
        $this->service = $service;
    }

    /**
     * Overview of users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->service->repository->getUsers();

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('user/index.html.twig', [
            'items' => $pagination,
            'archive' => false
        ]);
    }

    /**
     * Overview archived users
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archived(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->service->repository->getUsers(true);

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('user/archive.html.twig', [
            'items' => $pagination,
            'archive' => true
        ]);
    }

    /**
     * Add user
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $user = $this->service->newUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully added');
            $user = $form->getData();

            $result = $this->service->repository->save($user);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('User not added');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit user
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function edit($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {

        $user = $this->service->repository->getUser($id);

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            $user = $form->getData();

            $result = $this->service->repository->save($user);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('User not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Archive user
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully archived');
        $user = $this->service->repository->getUser($id);
        if (!is_object($user)) {
            $type = 'warning';
            $message = $translator->trans('User not found');
        }

        //Archive user
        $result = $this->service->archiveUser($user);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('User not archived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_users');
    }

    /**
     * Unarchive user
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function unarchive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully unarchived');
        $user = $this->service->repository->getUser($id);
        if (!is_object($user)) {
            $type = 'warning';
            $message = $translator->trans('User not found');
        }

        //Archive user
        $result = $this->service->archiveUser($user, false);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('User not unarchived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_users');
    }

    /**
     * Delete user action
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully deleted');
        $user = $this->service->repository->getUser($id);
        if (!is_object($user)) {
            $type = 'warning';
            $message = $translator->trans('User not found');
        }

        //Delete user
        $result = $this->service->repository->delete($user);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('User not deleted');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_users');
    }
}
