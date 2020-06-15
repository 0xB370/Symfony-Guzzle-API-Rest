<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Producto;
use App\Repository\ProductoRepository;
use App\Repository\TaxonomiaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\ NotFoundHttpException;

/**
* @Route("/producto")
*/
class ProductoController extends AbstractController{
    private $productoRepository;
    private $taxonomiaRepository;

    public function __construct(ProductoRepository $productoRepository, TaxonomiaRepository $taxonomiaRepository)
    {
        $this->productoRepository = $productoRepository;
        $this->taxonomiaRepository = $taxonomiaRepository;
    }

    /**
     * @Route("/add", name="addProducto", methods={"POST"})
     */
    public function add(Request $request): JsonResponse 
    {   
        $data = json_decode($request->getContent(), true);
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $precio = $data['precio'];
        $imagen = $data['imagen'];
        $taxonomia = $data['taxonomia'];
        $taxonomiaEnt = $this->taxonomiaRepository->find($taxonomia);
        if(!(empty($taxonomia)) && $taxonomiaEnt === null){
            throw new NotFoundHttpException('Taxonomía inexistente!');
        }
        if ( empty($nombre) || empty($precio) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $precioIVA = floatval($precio * 1.21);
        $this->productoRepository->saveProducto($nombre, $descripcion, $precio, $precioIVA, $imagen, $taxonomiaEnt);
        return new JsonResponse(['status' => 'Producto creado!'], Response::HTTP_CREATED);   
    }

    /**
     * @Route("/get/{id}", name="getProducto", methods={"GET"})
     */
    public function getOne($id): JsonResponse {
        $producto = $this->productoRepository->find($id);
        if ($producto === null){
            throw new NotFoundHttpException('Producto inexistente!');  
        }
        $data = [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'precioIVA' => $producto->getPrecioIVA(),
            'imagen' => $producto->getImagen(),
            'taxonomia' => $producto->getTaxonomia()->getNombre()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/getAll", name="getAllProductos", methods={"GET"})
     */
    public function getAll(): JsonResponse {
        $productos = $this->productoRepository->findAll();
        $data = [];
        foreach($productos as $producto){
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precioIVA' => $producto->getPrecioIVA(),
                'imagen' => $producto->getImagen(),
                'taxonomia' => $producto->getTaxonomia()->getNombre()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);     
    }

    /**
     * @Route("/update/{id}", name="updateProducto", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $producto = $this->productoRepository->find($id);
        if($producto === null){
            throw new NotFoundHttpException('Producto inexistente!');
        }
        $data = json_decode($request->getContent(), true);
        empty($data['nombre']) ? true : $producto->setNombre($data['nombre']);
        empty($data['descripcion']) ? true : $producto->setDescripcion($data['descripcion']);
        if (!empty($data['precio'])){
            $producto->setPrecio($data['precio']);
            $producto->setPrecioIVA(floatval($data['precio'] * 1.21));
        }
        empty($data['imagen']) ? true : $producto->setImagen($data['imagen']);
        if(!(empty($data['taxonomia']))){
            $taxonomiaEnt = $this->taxonomiaRepository->find($data['taxonomia']);
            if($taxonomiaEnt === null){
                throw new NotFoundHttpException('Taxonomía inexistente!');
            } else {
                $producto->setTaxonomia($taxonomiaEnt);
            }
        }
        
        $updatedProducto = $this->productoRepository->updateProducto($producto);
        return new JsonResponse($updatedProducto->toArray(), Response::HTTP_OK);
    }



    /**
     * @Route("/delete/{id}", name="deleteProducto", methods={"DELETE"})
     */
    public function delete($id): JsonResponse {
        $producto = $this->productoRepository->find($id);
        if($producto === null){
            throw new NotFoundHttpException('Producto inexistente!');
        }
        $this->productoRepository->removeProducto($producto);
        return new JsonResponse(['status' => 'Producto borrado'], Response::HTTP_OK);
    }

    /**
    * @Route("/getPagina/{pagina}/{numProductos}", name="getPaginaProducto", methods={"GET"})
    */
    public function getPagina($pagina=1, $numProductos=3): JsonResponse
    {
        $productos = $this->productoRepository->paginaProductos($pagina, $numProductos);
        $data = [];
        foreach($productos as $producto){
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precioIVA' => $producto->getPrecioIVA(),
                'imagen' => $producto->getImagen(),
                'taxonomia' => $producto->getTaxonomia()->getNombre()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
    * @Route("/findByTaxonomia/{taxonomia}/{pagina}/{numProductos}", 
    * name="getProductoPorTaxonomia", methods={"GET"})
    */
    public function findByTaxonomia($taxonomia, $pagina=1, $numProductos=3): JsonResponse
    {
        $productos = $this->productoRepository->buscarPorTaxonomia($taxonomia, $pagina, $numProductos);
        $data = [];
        foreach($productos as $producto){
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precioIVA' => $producto->getPrecioIVA(),
                'imagen' => $producto->getImagen(),
                'taxonomia' => $producto->getTaxonomia()->getNombre()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
    * @Route("/findByPrecio/{precioDesde}/{precioHasta}/{pagina}/{numProductos}", 
    * name="getProductoPorPrecio", methods={"GET"})
    */
    public function findByPrecio($precioDesde, $precioHasta, $pagina=1, $numProductos=3): JsonResponse
    {
        $productos = $this->productoRepository->buscarPorPrecio($precioDesde,$precioHasta, $pagina, $numProductos);
        $data = [];
        foreach($productos as $producto){
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precioIVA' => $producto->getPrecioIVA(),
                'imagen' => $producto->getImagen(),
                'taxonomia' => $producto->getTaxonomia()->getNombre()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
    * @Route("/findByText/{pagina}/{numProductos}", 
    * name="getProductoPorTexto", methods={"GET"})
    */
    public function findByText(Request $request, $pagina=1, $numProductos=3): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $texto = $data['texto'];
        $productos = $this->productoRepository->buscarPorTexto($texto, $pagina, $numProductos);
        $data = [];
        foreach($productos as $producto){
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precioIVA' => $producto->getPrecioIVA(),
                'imagen' => $producto->getImagen(),
                'taxonomia' => $producto->getTaxonomia()->getNombre()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
    * @Route("/getAllByText", 
    * name="getAllProductosPorTexto", methods={"GET"})
    */
    public function getAllByText(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $texto = $data['texto'];
        $productos = $this->productoRepository->buscarTodosPorTexto($texto);
        $data = [];
        foreach($productos as $producto){
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'precioIVA' => $producto->getPrecioIVA(),
                'imagen' => $producto->getImagen(),
                'taxonomia' => $producto->getTaxonomia()->getNombre()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
    
}