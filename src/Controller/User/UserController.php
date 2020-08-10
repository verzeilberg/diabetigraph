<?php

namespace App\Controller\User;

use App\Entity\Role;
use App\Form\User\UserFormType;
use App\Form\User\UserProfileFormType;
use App\Service\FileUploader;
use App\Service\Role\RoleService;
use App\Service\Route\RouteService;
use App\Service\User\UserProfileService;
use App\Form\User\RegistrationType;
use App\Service\User\UserService;
use App\Verzeilberg\UploadImage\UploadImagesBundle;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Security\TokenGenerator;
use App\Service\Security\Mailer;
use App\Service\Security\CaptchaValidator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use App\Entity\User;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Verzeilberg\UploadImagesBundle\Service\Rotate;

class UserController extends AbstractController
{

    /** @var UserProfileService */
    private $service;

    private $userService;


    public function __construct(
        UserProfileService $service,
        UserService $userService
    ) {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @param UserInterface $user
     * @return Response
     */
    public function profile(UserInterface $user)
    {
        $iets = new Rotate();

        $iets->Rotate();

        if(!is_object($user->getUserProfile())) {
            $userProfile = $this->service->newUserProfile();
            $userProfile->setUser($user);
            $this->service->repository->save($userProfile);
            $user->setUserProfile($userProfile);
            $result = $this->userService->saveUser($user, false, false);
        }
        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param UserInterface $user
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function edit(UserInterface $user, Request $request, TranslatorInterface $translator, LoggerInterface $logger, FileUploader $fileUploader)
    {
        $userProfile = $user->getUserProfile();

        $form = $this->createForm(UserProfileFormType::class, $userProfile, [
            'useImageUpload' => false
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = 'success';
            $message = $translator->trans('Successfully edited');
            $userProfile = $form->getData();

            $result = $this->service->repository->save($userProfile);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Profile not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_userprofile');
        }

        return $this->render('user/profile-edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    public function editImage(UserInterface $user, TranslatorInterface $translator, Request $request)
    {

        $type = 'success';
        $message = $translator->trans('Image successfully edited');
        $userProfile = $user->getUserProfile();

        $form = $this->createForm(UserProfileFormType::class, $userProfile, [
            'useOnlyImageUpload' => true
        ]);

        if ($form->isSubmitted()) {

            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_userprofile');
        }

        return $this->render('user/profile-edit-image.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/image", name="image", options={"expose"=true})
     * @param Request $request
     */
    public function getImage(UserInterface $user, Request $request)
    {

        $userProfile = $user->getUserProfile();

        //File
        $file = $_FILES['file'];
        $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);
        $fileName = $this->generateUniqueName() . '.' . $file->guessExtension();
        $file->move(
            $this->getTargetDir(),
            $fileName
        );

        $userProfile->setAvater($fileName);
        $result = $this->service->repository->save($userProfile);
            return new JsonResponse([
                'result' => $result
            ]
            );
    }

    private function generateUniqueName()
    {
        return md5(uniqid());
    }

    private function getTargetDir()
    {
        return $this->getParameter('images_directory');
    }

}