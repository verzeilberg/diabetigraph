<?php

namespace App\Controller;

use App\Form\ProductGroupFormType;
use App\Service\ProductGroupService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductGroupController extends AbstractController
{
    /** @var productGroupServiceGroup */
    private $productGroupService;

    public function __construct(
        ProductGroupService $productGroupService
    ) {
        $this->productGroupService = $productGroupService;
    }


    /**
     * Overview of product groups
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->productGroupService->productGroupRepository->getProductGroups();

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('product-group/index.html.twig', [
            'items' => $pagination,
            'archive' => false
        ]);
    }

    /**
     * Overview archived product group(s)
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function archived(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->productGroupService->productGroupRepository->getProductGroups(true);

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('product-group/archive.html.twig', [
            'items' => $pagination,
            'archive' => true
        ]);
    }

    /**
     * Add product group
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $productGroup = $this->productGroupService->newProductGroup();
        $form = $this->createForm(ProductGroupFormType::class, $productGroup);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully added');
            $productGroup = $form->getData();

            $result = $this->productGroupService->productGroupRepository->save($productGroup);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Product group not added');
            }
            $this->addFlash(
                $type,
                $message
            );
            return $this->redirectToRoute('app_productgroups');
        }

        return $this->render('product-group/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit product group
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $productGroup = $this->productGroupService->productGroupRepository->getProductGroup($id);

        $form = $this->createForm(ProductGroupFormType::class, $productGroup);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            $productGroup = $form->getData();

            $result = $this->productGroupService->productGroupRepository->save($productGroup);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Product group not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_productgroups');
        }

        return $this->render('product-group/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Archive product group
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function archive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully archived');
        $productGroup = $this->productGroupService->productGroupRepository->getProductGroup($id);
        if (!is_object($productGroup)) {
            $type = 'warning';
            $message = $translator->trans('Product group not found');
        }

        //Archive product
        $result = $this->productGroupService->archiveProductGroup($productGroup);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Product group not archived');
        }
        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_productgroups');
    }

    /**
     * Unarchive product group
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function unarchive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully unarchived');
        $productGroup = $this->productGroupService->productGroupRepository->getProductGroup($id);
        if (!is_object($productGroup)) {
            $type = 'warning';
            $message = $translator->trans('Product group not found');
        }

        //Archive product
        $result = $this->productGroupService->archiveProductGroup($productGroup, false);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Product group not unarchived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_productgroups');
    }

    /**
     * Delete product group
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully deleted');
        $productGroup = $this->productGroupService->productGroupRepository->getProductGroup($id);
        if (!is_object($productGroup)) {
            $type = 'warning';
            $message = $translator->trans('Product group not found');
        }

        //Delete product
        $result = $this->productGroupService->productGroupRepository->delete($productGroup);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Product group not deleted');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_productgroups');
    }
}
