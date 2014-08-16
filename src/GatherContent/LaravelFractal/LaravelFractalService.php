<?php namespace GatherContent\LaravelFractal;

use League\Fractal\Pagination\PaginatorInterface;
use Response;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceInterface;

class LaravelFractalService
{
    private $manager;
    private $paginatorAdapter;

    public function __construct(Manager $manager, $paginatorAdapter)
    {
        $this->manager = $manager;
        $this->paginatorAdapter = $paginatorAdapter;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function item($item, TransformerAbstract $transformer)
    {
        $resource = new Item($item, $transformer);
        return $this->buildResponse($resource);
    }

    public function collection($items, TransformerAbstract $transformer, PaginatorInterface $paginator = null)
    {
        $resource = new Collection($items, $transformer);
        
        if ($paginator) {
            $adapter = new $this->paginatorAdapter($paginator);
            $resource->setPaginator($adapter);
        }
        
        return $this->buildResponse($resource);
    }

    private function buildResponse(ResourceInterface $resource)
    {
        $data = $this->manager->createData($resource);
        return Response::make($data->toArray());
    }
}
