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

    public function collection($items, TransformerAbstract $transformer, IlluminatePaginatorAdapter $adapter = null)
    {
        $resources = new Collection($items, $transformer);

        if ($items instanceof IlluminatePaginator) {
            $this->paginateCollection($resources, $items, $adapter);
        }
        
        return $this->buildResponse($resources);
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
