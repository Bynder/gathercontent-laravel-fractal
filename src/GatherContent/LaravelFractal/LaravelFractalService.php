<?php namespace GatherContent\LaravelFractal;

use Response;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class LaravelFractalService
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
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
            $adapter = new IlluminatePaginatorAdapter($paginator);
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
