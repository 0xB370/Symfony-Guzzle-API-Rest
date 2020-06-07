<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Taxonomia;
use App\Repository\TaxonomiaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\ NotFoundHttpException;

/**
* @Route("/taxonomia")
*/
class TaxonomiaController extends AbstractController{
    private $taxonomiaRepository;

    public function __construct(TaxonomiaRepository $taxonomiaRepository)
    {
        $this->taxonomiaRepository = $taxonomiaRepository;
    }

    /**
     * @Route("/add", name="addTaxonomia", methods={"POST"})
     */
    public function add(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $imagen = $data['imagen'];
        if ( empty($nombre) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $this->taxonomiaRepository->saveTaxonomia($nombre, $descripcion, $imagen);
        return new JsonResponse(['status' => 'Taxonomia creada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="getTaxonomia", methods={"GET"})
     */
    public function getOne($id): JsonResponse {
        $taxonomia = $this->taxonomiaRepository->find($id);
        if ($taxonomia === null){
            throw new NotFoundHttpException('Taxonomia inexistente!');
            
        }
        $data = [
            'id' => $taxonomia->getId(),
            'nombre' => $taxonomia->getNombre(),
            'descripcion' => $taxonomia->getDescripcion(),
            'imagen' => $taxonomia->getImagen()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/getAll", name="getAllTaxonomias", methods={"GET"})
     */
    public function getAll(): JsonResponse {
        $taxonomias = $this->taxonomiaRepository->findAll();
        $data = [];
        foreach($taxonomias as $taxonomia){
            $data[] = [
                'id' => $taxonomia->getId(),
                'nombre' => $taxonomia->getNombre(),
                'descripcion' => $taxonomia->getDescripcion(),
                'imagen' => $taxonomia->getImagen()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="updateTaxonomia", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse {
        $taxonomia = $this->taxonomiaRepository->find($id);
        if($taxonomia === null){
            throw new NotFoundHttpException('Taxonomia inexistente!');
        }
        $data = json_decode($request->getContent(), true);
        empty($data['nombre']) ? true : $taxonomia->setNombre($data['nombre']);
        empty($data['descripcion']) ? true : $taxonomia->setDescripcion($data['descripcion']);
        empty($data['imagen']) ? true : $taxonomia->setImagen($data['imagen']);
        $updatedTaxonomia = $this->taxonomiaRepository->updateTaxonomia($taxonomia);
        return new JsonResponse($updatedTaxonomia->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/delete/{id}", name="deleteTaxonomia", methods={"DELETE"})
     */
    public function delete($id): JsonResponse {
        $taxonomia = $this->taxonomiaRepository->find($id);
        if($taxonomia === null){
            throw new NotFoundHttpException('Taxonomia inexistente!');
        }
        $this->taxonomiaRepository->removeTaxonomia($taxonomia);
        return new JsonResponse(['status' => 'Taxonomia borrada'], Response::HTTP_OK);
    }

    /**
    * @Route("/getPagina/{pagina}/{numTaxonomias}", name="getPaginaTaxonomia", methods={"GET"})
    */
    public function getPagina($pagina=1, $numTaxonomias=3): JsonResponse
    {
        $taxonomias = $this->taxonomiaRepository->paginaTaxonomias($pagina, $numTaxonomias);
        $data = [];
        foreach($taxonomias as $taxonomia){
            $data[] = [
                'id' => $taxonomia->getId(),
                'nombre' => $taxonomia->getNombre(),
                'descripcion' => $taxonomia->getDescripcion(),
                'imagen' => $taxonomia->getImagen()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
    * @Route("/findByText/{pagina}/{numTaxonomias}", 
    * name="getTaxonomiaPorTexto", methods={"GET"})
    */
    public function findByText(Request $request, $pagina=1, $numTaxonomias=3): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $texto = $data['texto'];
        $taxonomias = $this->taxonomiaRepository->buscarPorTexto($texto, $pagina, $numTaxonomias);
        $data = [];
        foreach($taxonomias as $taxonomia){
            $data[] = [
                'id' => $taxonomia->getId(),
                'nombre' => $taxonomia->getNombre(),
                'descripcion' => $taxonomia->getDescripcion(),
                'imagen' => $taxonomia->getImagen()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

}