<?php

namespace App\Controller\Role;

use App\Form\RoleFormType;
use App\Service\Role\RoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;

class RoleController extends AbstractController
{
    /** @var RoleService */
    private $service;

    public function __construct(
        roleService $roleService
    ) {
        $this->service = $roleService;
    }


    /**
     * Overview of roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->service->repository->getRoles();

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('role/index.html.twig', [
            'items' => $pagination,
            'archive' => false
        ]);
    }

    /**
     * Overview archived roles
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archived(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->service->repository->getRoles(true);

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('role/archive.html.twig', [
            'items' => $pagination,
            'archive' => true
        ]);
    }

    /**
     * Add role
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $role = $this->service->newRole();
        $form = $this->createForm(RoleFormType::class, $role);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully added');
            $role = $form->getData();

            $result = $this->service->repository->save($role);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Role not added');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_roles');
        }

        return $this->render('role/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit role
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

        $role = $this->service->repository->getRole($id);

        $form = $this->createForm(RoleFormType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            $role = $form->getData();

            $result = $this->service->repository->save($role);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Role not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_roles');
        }

        return $this->render('role/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Archive role
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
        $role = $this->service->repository->getRole($id);
        if (!is_object($role)) {
            $type = 'warning';
            $message = $translator->trans('Role not found');
        }

        //Archive role
        $result = $this->service->archiveRole($role);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Role not archived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_roles');
    }

    /**
     * Unarchive role
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
        $role = $this->service->repository->getRole($id);
        if (!is_object($role)) {
            $type = 'warning';
            $message = $translator->trans('Role not found');
        }

        //Archive role
        $result = $this->service->archiveRole($role, false);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Role not unarchived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_roles');
    }

    /**
     * Delete role action
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
        $role = $this->service->repository->getRole($id);
        if (!is_object($role)) {
            $type = 'warning';
            $message = $translator->trans('Role not found');
        }

        //Delete role
        $result = $this->service->repository->delete($role);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Role not deleted');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_roles');
    }
}
