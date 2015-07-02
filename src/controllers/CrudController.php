<?php
namespace Serverfireteam\Panel;


/*  
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Controller;

/**
 * Class CrudController
 * @package Serverfireteam\Panel
 */
class CrudController extends Controller
{
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var
     */
    protected $grid;
    /**
     * @var array
     */
    protected $entity;
    /**
     * @var
     */
    protected $edit;
    /**
     * @var
     */
    protected $filter;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (self::$router) {
            $params       = self::$router->current()->parameters();
            $this->entity = $params['entity'];
        } else {
            $this->entity = [];
        }
    }

    /**
     * @param string $entity name of the entity
     */
    public function all($entity)
    {
    }

    /**
     * @param string $entity name of the entity
     */
    public function edit($entity)
    {
    }


    /**
     * @return array
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $orderByColumn
     * @param int    $paginateCount
     */
    public function addStylesToGrid($orderByColumn = 'id', $paginateCount = 10)
    {
        $this->grid->edit('edit', 'Edit', 'show|modify|delete');

        $this->grid->orderBy($orderByColumn, 'desc');
        $this->grid->paginate($paginateCount);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function returnView()
    {
        $configs = Link::returnUrls();

        if (!isset($configs) || $configs == null) {
            throw new \Exception('NO URL is set for yet');
        } else {
            if (!in_array($this->entity, $configs)) {
                throw new \Exception('This url is not set yet!');
            } else {
                return $this->app->make('view')->make(
                    'panelViews::all',
                    [
                        'grid'           => $this->grid,
                        'filter'         => $this->filter,
                        'current_entity' => $this->entity,
                        'import_message' => ($this->app->make('session')->has('import_message')) ? $this->app->make(
                            'session'
                        )->get('import_message') : '',
                    ]
                );
            }
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function returnEditView()
    {
        $configs = Link::returnUrls();

        if (!isset($configs) || $configs == null) {
            throw new \Exception('NO URL is set for yet');
        } else {
            if (!in_array($this->entity, $configs)) {
                throw new \Exception('This url is set yet !');
            } else {
                return $this->app->make('view')->make(
                    'panelViews::edit',
                    [
                        'edit' => $this->edit,
                    ]
                );
            }
        }
    }

    /**
     *
     */
    public function finalizeFilter()
    {
        $lang = $this->app->make('lang');
        $this->filter->submit($lang->get('panel::fields.search'));
        $this->filter->reset($lang->get('panel::fields.reset'));
    }
}
