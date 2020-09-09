<?php

namespace App\Controller;

use App\Form\UnitFormType;
use App\Service\UnitService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;

class UnitController extends AbstractController
{
    /** @var unitService */
    private $unitService;

    public function __construct(
        UnitService $unitService
    ) {
        $this->unitService = $unitService;
    }


    /**
     * Overview of unit groups
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->unitService->unitRepository->getUnits();

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('unit/index.html.twig', [
            'items' => $pagination,
            'archive' => false
        ]);
    }

    /**
     * Overview archived unit group(s)
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function archived(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->unitService->unitRepository->getUnits(true);

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('unit/archive.html.twig', [
            'items' => $pagination,
            'archive' => true
        ]);
    }

    /**
     * Add unit group
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $unit = $this->unitService->newUnit();
        $unit = $this->unitService->setOrderNumber($unit);

        $form = $this->createForm(UnitFormType::class, $unit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully added');
            $unit = $form->getData();

            $result = $this->unitService->unitRepository->save($unit);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Unit not added');
            }
            $this->addFlash(
                $type,
                $message
            );
            return $this->redirectToRoute('app_units');
        }

        return $this->render('unit/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit unit group
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
        $unit = $this->unitService->unitRepository->getUnit($id);

        $form = $this->createForm(UnitFormType::class, $unit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            $unit = $form->getData();

            $result = $this->unitService->unitRepository->save($unit);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Unit not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_units');
        }

        return $this->render('unit/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Archive unit group
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function archive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully archived');
        $unit = $this->unitService->unitRepository->getUnit($id);
        if (!is_object($unit)) {
            $type = 'warning';
            $message = $translator->trans('Unit not found');
        }

        //Archive unit
        $result = $this->unitService->archiveUnit($unit);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Unit not archived');
        }
        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_units');
    }

    /**
     * Unarchive unit group
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function unarchive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully unarchived');
        $unit = $this->unitService->unitRepository->getUnit($id);
        if (!is_object($unit)) {
            $type = 'warning';
            $message = $translator->trans('Unit not found');
        }

        //Archive unit
        $result = $this->unitService->archiveUnit($unit, false);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Unit not unarchived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_units');
    }

    /**
     * Delete unit group
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
        $unit = $this->unitService->unitRepository->getUnit($id);
        if (!is_object($unit)) {
            $type = 'warning';
            $message = $translator->trans('Unit not found');
        }

        //Delete unit
        $result = $this->unitService->unitRepository->delete($unit);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Unit not deleted');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_units');
    }

    public function order(Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $success = true;
        $message = $translator->trans('Unit saved');

        if($request->isXmlHttpRequest())
        {
            $items = $request->get('items');
            foreach ($items as $index => $item) {
                $order = $index + 1;
                $unit = $this->unitService->unitRepository->getUnit($item);
                if (is_object($unit)) {
                    $unit->setOrder($order);
                    $result = $this->unitService->unitRepository->save($unit);
                    if (!$result) {
                        $success = false;
                        $message = $translator->trans('Unit not saved');
                        break;
                    }
                } else {
                    $success = false;
                    $message = $translator->trans('Something went wrong');
                    break;
                }

            }
        } else {
            $success = false;
            $message = $translator->trans('Not an ajax call');
        }
        return new JsonResponse([
            'success' => $success,
            'message' => $message
        ]);
    }
}
