<?php namespace GatherContent\LaravelFractal;

use Response;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use Illuminate\Pagination\Paginator as IlluminatePaginator;

class LaravelFractalService
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
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

    public function collection($items, TransformerAbstract $transformer, IlluminatePaginator $paginator = null, IlluminatePaginatorAdapter $adapter = null)
    {
        $resource = new Collection($items, $transformer);
        
        if ($paginator) {
            $this->paginateCollection($resource, $paginator, $adapter);
        }
        
        return $this->buildResponse($resource);
    }

    private function paginateCollection(Collection $collection, IlluminatePaginator $paginator, IlluminatePaginatorAdapter $adapter = null)
    {
        if (is_null($adapter)) {
            $adapter = new IlluminatePaginatorAdapter($paginator);
        }
        
        $collection->setPaginator($adapter);
    }

    private function buildResponse(ResourceInterface $resource)
    {
        $data = $this->manager->createData($resource);
        return Response::make($data->toArray());
    }
}
