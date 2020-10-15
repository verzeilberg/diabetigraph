<?php

namespace App\Controller\Admin\User;

use App\Entity\Role;
use App\Form\User\UserFormType;
use App\Service\Role\RoleService;
use App\Service\Route\RouteService;
use App\Service\User\UserService;
use App\Form\User\RegistrationType;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

class UserController extends AbstractController
{

    const DOUBLE_OPT_IN = false;

    /** @var UserService */
    private $service;

    /** @var RoleService */
    private $roleService;

    public function __construct(
        UserService $service,
        RoleService $roleService
    ) {
        $this->service = $service;
        $this->roleService = $roleService;
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


        return $this->render('admin/user/index.html.twig', [
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


        return $this->render('admin/user/archive.html.twig', [
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
            $result = $this->service->saveUser($user, true, true);
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
        return $this->render('admin/user/add.html.twig', [
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

        //Get original password from
        $originalPassword = $user->getPassword();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            //Check if user has a changed password
             $plainPassword = $form->getData()->getPassword();
             $newPassword = (empty($plainPassword)? false:true);
             if(!$newPassword) {
                 $user->setPassword($originalPassword);
             }
            $result = $this->service->saveUser($user, $newPassword);
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

        return $this->render('admin/user/edit.html.twig', [
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

    /**
     * @param Request $request
     * @param TokenGenerator $tokenGenerator
     * @param UserPasswordEncoderInterface $encoder
     * @param Mailer $mailer
     * @param CaptchaValidator $captchaValidator
     * @param TranslatorInterface $translator
     * @param TokenStorageInterface $tokenStorage
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Throwable
     */
    public function register(
        Request $request,
        TokenGenerator $tokenGenerator,
        UserPasswordEncoderInterface $encoder,
        Mailer $mailer,
        CaptchaValidator $captchaValidator,
        TranslatorInterface $translator,
        TokenStorageInterface $tokenStorage
    )
    {
        //Get logged on user
        $user = $tokenStorage->getToken()->getUser();
        //If user is logged on redirect to index
        if (is_object($user)) {
            $this->addFlash('warning', $translator->trans('You al already logged on, there for you can\'t register'));
            return $this->redirect($this->generateUrl('app_index'));
        }

        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            try {
                if (!$captchaValidator->validateCaptcha($request->get('g-recaptcha-response'))) {
                    $form->addError(new FormError($translator->trans('captcha.wrong')));
                    throw new ValidatorException('captcha.wrong');
                }

                $role = $this->roleService->repository->getRole(6);

                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $user->setIsActive(false);
                $user->setCreatedAt(new \DateTime());
                $user->setUpdatedAt(new \DateTime());

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                if (self::DOUBLE_OPT_IN) {
                    $mailer->sendActivationEmailMessage($user);
                    $this->addFlash('success', 'user.activation-link');
                    return $this->redirect($this->generateUrl('index'));
                }

                return $this->redirect($this->generateUrl('app_activateuser', ['token' => $token]));

            } catch (ValidatorException $exception) {

            }
        }


        return $this->render('admin/user/register.html.twig', [
            'form' => $form->createView(),
            'captchakey' => $captchaValidator->getKey()
        ]);
    }

    /**
     * @param $request Request
     * @param $user User
     * @param $authenticatorHandler GuardAuthenticatorHandler
     * @param $loginFormAuthenticator LoginFormAuthenticator
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Exception
     */
    public function activate(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator)
    {
        $user->setIsActive(true);
        $user->setToken(null);
        $user->setActivatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Welcome user');

        // automatic login
        return $authenticatorHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $loginFormAuthenticator,
            'main'
        );
    }

}
