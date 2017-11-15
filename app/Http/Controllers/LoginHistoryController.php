<?php

namespace IXP\Http\Controllers;

/*
 * Copyright (C) 2009-2017 Internet Neutral Exchange Association Company Limited By Guarantee.
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

use Auth, D2EM, Route;

use Entities\{
    UserLoginHistory    as UserLoginHistoryEntity,
    User                as UserEntity
};

use Illuminate\View\View;


/**
 * Login History Controller
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @author     Yann Robin <yann@islandbridgenetworks.ie>
 * @category   VlanInterface
 * @copyright  Copyright (C) 2009-2017 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class LoginHistoryController extends Doctrine2Frontend {
    /**
     * The object being added / edited
     * @var UserLoginHistoryEntity
     */
    protected $object = null;

    /**
    * The minimum privileges required to access this controller.
    *
    * If you set this to less than the superuser, you need to manage privileges and access
    * within your own implementation yourself.
    *
    * @var int
    */
    public static $minimum_privilege = UserEntity::AUTH_SUPERUSER;

    /**
     * This function sets up the frontend controller
     */
    public function feInit(){

        $this->feParams         = (object)[
            'entity'            => UserLoginHistoryEntity::class,

            'pagetitle'         => 'Login History',

            'titleSingular'     => 'Login History',
            'nameSingular'      => 'a Login History',

            'listOrderBy'       => 'lastlogin',
            'listOrderByDir'    => 'DESC',

            'readonly'       => 'true',

            'viewFolderName'    => 'login-history',

            'listColumns'    => [

                'username'          => [ 'title' => 'Username' ],
                'email'             => [ 'title' => 'Email' ],
                'cust_name'  => [
                    'title'      => 'Customer',
                    'type'       => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                    'controller' => 'customer',
                    'action'     => 'overview',
                    'nameIdParam'=> 'id',
                    'idField'    => 'cust_id'
                ],
                'lastlogin'         => [
                    'title'     => 'Last Login',
                    'type'      => self::$FE_COL_TYPES[ 'DATETIME' ]
                ]
            ]
        ];

        // display the same information in the view as the list
        $this->feParams->viewColumns = $this->feParams->listColumns;

        // custom access controls:
        switch( Auth::user()->getPrivs() ) {
            case UserEntity::AUTH_SUPERUSER:
                break;

            default:
                abort( 403 );
        }

    }

    /**
     * @inheritdoc
     */
    public static function routes() {
        Route::group( [ 'prefix' => 'login-history' ], function() {
            Route::get(  'list',                'LoginHistoryController@list'   )->name( 'login-history@list'   );
            Route::get(  'view/{id}/{limit?}',  'LoginHistoryController@view'   )->name( 'login-history@view'   );
        });
    }


    /**
     * Provide array of rows for the list and view
     *
     * @param int $id The `id` of the row to load for `view`. `null` if `list`
     * @return array
     */
    protected function listGetData( $id = null ) {
        return D2EM::getRepository( UserEntity::class)->getLastLoginsForFeList( $this->feParams );
    }


    /**
     * Display the login history list for a user
     *
     * @param int $id the id of the user
     * @param boolean $limit do we want to limit the number of row displayd ?
     *
     * @return View
     */
    public function view( $id, $limit = false ): View
    {

        if( !( $user = D2EM::getRepository( UserEntity::class )->find( $id ) ) ) {
            abort(404 );
        }

        return view( 'login-history/view' )->with([
            'histories'                 => D2EM::getRepository( UserLoginHistoryEntity::class)->getAllForFeList( $user->getId(), $limit ),
            'user'                      => $user,
        ]);
    }
}
