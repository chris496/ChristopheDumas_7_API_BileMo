<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('api/client/{idClient}/users', name: 'users', methods: ['GET'])]
    public function getUserList(Request $request, int $idClient, UserRepository $userRepository, ClientRepository $clientRepository, SerializerInterface $serializer, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "getAllUsers-" . $page . "-" . $limit;
        $userList = $cachePool->get($idCache, function (ItemInterface $item) use ($userRepository, $page, $limit, $idClient) {
            $item->tag("usersCache");
            return $userRepository->findAllWithPagination($page, $limit, $idClient);
        });
        $context = SerializationContext::create()->setGroups(['getUsers']);
        $jsonUserList = $serializer->serialize($userList, 'json', $context);
        
        return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
    }

    #[Route('api/client/{idClient}/users/{id}', name: 'detailUser', methods: ['GET'])]
    public function getOneUser(int $idClient, int $id, User $user, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $oneUser = $userRepository->findUser($idClient, $id);
        $context = SerializationContext::create()->setGroups(['getUsers']);
        $jsonUserList = $serializer->serialize($user, 'json', $context);
        
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('api/client/{idClient}/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteOneUser(User $user, EntityManagerInterface $em, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $cachePool->invalidateTags(["usersCache"]);
        $em->remove($user);
        $em->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/client/{idClient}/users', name: 'createUser', methods: ['POST'])]
    public function createOneUser($idClient, Request $request, ClientRepository $clientRepository, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        $errors = $validator->validate($user);

        if($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $content = $request->toArray();
        $idClient = $content['idClient'];
        $user->setClient($clientRepository->find($idclient));
        
        $em->persist($user);
        $em->flush();

        //$oneUser = $userRepository->findUser($idClient, $id);
        $context = SerializationContext::create()->setGroups(['getUsers']);
        $jsonUser = $serializer->serialize($user, 'json', $context);
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
        
    }
}
