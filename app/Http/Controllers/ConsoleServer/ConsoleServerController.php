<?php

namespace IXP\Http\Controllers\ConsoleServer;

/*
 * Copyright (C) 2009 - 2020 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

use Former;

use Illuminate\Database\Eloquent\Builder;
use IXP\Rules\IdnValidate;

use Illuminate\Http\{
    Request,
    RedirectResponse
};

use IXP\Models\{
    Cabinet,
    ConsoleServer,
    Vendor
};

use IXP\Utils\Http\Controllers\Frontend\EloquentController;

/**
 * ConsoleServer Controller
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @author     Yann Robin <yann@islandbridgenetworks.ie>
 * @category   Controller
 * @copyright  Copyright (C) 2009 - 2020 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class ConsoleServerController extends EloquentController
{
    /**
     * The object being created / edited
     * @var ConsoleServer
     */
    protected $object = null;

    /**
     * This function sets up the frontend controller
     */
    public function feInit(): void
    {
        $this->feParams         = (object)[
            'entity'            => ConsoleServer::class,
            'pagetitle'         => 'Console Servers',
            'titleSingular'     => 'Console Server',
            'nameSingular'      => 'a console server',
            'listOrderBy'       => 'id',
            'listOrderByDir'    => 'ASC',
            'viewFolderName'    => 'console-server',

            'listColumns'    => [
                'name'           => [
                    'title'      => 'Name',
                    'type'       => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                    'controller' => 'console-server-connection',
                    'action'     => 'list/port',
                    'idField'    => 'id'
                ],
                'facility'  => [
                    'title'      => 'Facility',
                    'type'       => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                    'controller' => 'facility',
                    'action'     => 'view',
                    'idField'    => 'locationid'
                ],
                'cabinet'  => [
                    'title'      => 'Cabinet',
                    'type'       => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                    'controller' => 'rack',
                    'action'     => 'view',
                    'idField'    => 'cabinetid'
                ],
                'vendor'  => [
                    'title'       => 'Vendor',
                    'type'        => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                    'controller'  => 'vendor',
                    'action'      => 'view',
                    'idField'     => 'vendorid'
                ],
                'model'           => 'Model',
                'num_connections' => [
                    'title'      => 'Connections',
                    'type'       => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                    'controller' => 'console-server-connection',
                    'action'     => 'list/port',
                    'idField'    => 'id'
                ],
                'active'       => [
                    'title'    => 'Active',
                    'type'     => self::$FE_COL_TYPES[ 'YES_NO' ]
                ]
            ]
        ];

        // display the same information in the view as the list
        $this->feParams->viewColumns = array_merge(
            $this->feParams->listColumns,
            [
                'serialNumber'   => 'Serial Number',
                'notes'       => [
                    'title'         => 'Notes',
                    'type'          => self::$FE_COL_TYPES[ 'PARSDOWN' ]
                ]
            ]
        );
    }

    /**
     * Provide array of rows for the list action and view action
     *
     * @param int|null $id The `id` of the row to load for `view` action`. `null` if `listAction`
     *
     * @return array
     */
    protected function listGetData( $id = null ): array
    {
        $feParams = $this->feParams;
        return ConsoleServer::selectRaw(
            'cs.*,
            v.id AS vendorid, v.name AS vendor,
            c.id AS cabinetid, c.name AS cabinet, 
            l.id AS locationid, l.shortname AS facility,
            COUNT( DISTINCT csc.id ) AS num_connections'
        )
            ->from( 'console_server AS cs' )
            ->leftJoin( 'consoleserverconnection AS csc', 'csc.console_server_id', 'cs.id')
            ->leftJoin( 'cabinet AS c', 'c.id', 'cs.cabinet_id')
            ->leftJoin( 'location AS l', 'l.id', 'c.locationid')
            ->leftJoin( 'vendor AS v', 'v.id', 'cs.vendor_id')
            ->when( $id , function( Builder $q, $id ) {
                return $q->where('cs.id', $id );
            } )->when( $feParams->listOrderBy , function( Builder $q, $orderby ) use ( $feParams )  {
                return $q->orderBy( $orderby, $feParams->listOrderByDir ?? 'ASC');
            })
            ->groupBy('cs.id' )->get()->toArray();
    }

    /**
     * Display the form to create an object
     *
     * @return array
     */
    protected function createPrepareForm(): array
    {
        return [
            'object'        => $this->object,
            'cabinets'      => Cabinet::selectRaw( "id, concat( name, ' [', colocation, ']') AS name" )
                ->orderBy( 'name', 'asc' )
                ->get(),
            'vendors'       => Vendor::orderBy( 'name' )->get(),
        ];
    }

    /**
     * Display the form to edit an object
     *
     * @param   int $id ID of the row to edit
     *
     * @return array
     */
    protected function editPrepareForm( int $id ): array
    {
        $this->object = ConsoleServer::findOrFail( $id );

        Former::populate([
            'name'              => request()->old( 'name',             $this->object->name ),
            'hostname'          => request()->old( 'hostname',         $this->object->hostname ),
            'model'             => request()->old( 'model',            $this->object->model ),
            'serialNumber'      => request()->old( 'serial_number',    $this->object->serialNumber ),
            'cabinet_id'        => request()->old( 'cabinet',          $this->object->cabinet_id ),
            'vendor_id'         => request()->old( 'vendor',           $this->object->vendor_id ),
            'active'            => request()->old( 'active',           ( $this->object->active ? 1 : 0 ) ),
            'notes'             => request()->old( 'notes',             $this->object->note ),
        ]);

        return [
            'object'        => $this->object,
            'cabinets'      => Cabinet::selectRaw( "id, concat( name, ' [', colocation, ']') AS name" )
                ->orderBy( 'name', 'asc' )
                ->get(),
            'vendors'       => Vendor::orderBy( 'name' )->get()
        ];
    }

    /**
     * Check if the form is valid
     *
     * @param $request
     */
    public function checkForm( Request $request ): void
    {
        $request->validate( [
            'name' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use( $request ) {
                    $cs = ConsoleServer::whereName( $value )->first();
                    if( $cs && $cs->exists() && $cs->id !== (int)$request->id ) {
                        return $fail( 'The name must be unique.' );
                    }
                },
            ],
            'vendor_id'            => [ 'required', 'integer',
                function( $attribute, $value, $fail ) {
                    if( !Vendor::whereId( $value )->exists() ) {
                        return $fail( 'Vendor is invalid / does not exist.' );
                    }
                }
            ],
            'cabinet_id'            => [ 'required', 'integer',
                function( $attribute, $value, $fail ) {
                    if( !Cabinet::whereId( $value )->exists() ) {
                        return $fail( 'Vendor is invalid / does not exist.' );
                    }
                }
            ],
            'model'             => 'nullable|string|max:255',
            'serialNumber'     => 'nullable|string',
            'notes'             => 'nullable|string',
            'hostname'          => [ 'required','string', new IdnValidate() ],
            'active'            => 'string'
        ] );
    }

    /**
     * Function to do the actual validation and storing of the submitted object.
     *
     * @param Request $request
     *
     * @return bool|RedirectResponse
     *
     * @throws
     */
    public function doStore( Request $request )
    {
        $this->checkForm( $request );
        $this->object = ConsoleServer::create( $request->all() );

        return true;
    }

    /**
     * Function to do the actual validation and storing of the submitted object.
     *
     * @param Request   $request
     * @param int       $id
     *
     * @return bool|RedirectResponse
     *
     * @throws
     */
    public function doUpdate( Request $request, int $id )
    {
        $this->object = ConsoleServer::findOrFail( $id );
        $this->checkForm( $request );
        $this->object->update( $request->all() );

        return true;
    }

    /**
     * Delete all console server connections before deleting the console server.
     *
     * @inheritdoc
     *
     * @return bool Return false to stop / cancel the deletion
     */
    protected function preDelete(): bool
    {
        $this->object->consoleServerConnections()->delete();
        return true;
    }
}