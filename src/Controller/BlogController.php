<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/blog", name="blog")
 */

class BlogController extends AbstractController
{

    /**
     * @Route("/{page}", name="_list", defaults={"page": 5}, requirements={"page"="\d+" })
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function($item){
                    if(!empty($item->getSlug()))
                        return $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="_post_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post): Response
    {
        // using param converter
        return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="_by_slug", methods={"GET"})
     */
    public function postBySlug(BlogPost $post): Response
    {
        // same as findBy (uses the param in route as column to search in the sepcified entity)
        return $this->json($post);
    }

    /**
     * @Route("/post/add", name="_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var $serializer Serializer */
        $serializer = $this->get('serializer');
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $post){
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
