<?php

namespace IXP\Http\Middleware;

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
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GpNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */
use Auth, Closure;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use IXP\Models\CustomerToUser;

/**
 * Middleware: Ensure authentification
 *
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @author     Yannr Robin <yann@islandbridgenetworks.ie>
 * @category   IXP
 * @package    IXP\Http\Controllers\Doctrine2Frontend
 * @copyright  Copyright (C) 2009 - 2020 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class Authenticate
{
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
     *
	 * @return void
	 */
	public function __construct( Guard $auth )
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param   Request     $r
	 * @param   Closure     $next
     *
	 * @return mixed
	 */
	public function handle( Request $r, Closure $next )
	{
		if( $this->auth->guest() ) {
			if( $r->ajax() ) {
				return response('Unauthorized.', 401);
			}
			return redirect()->guest(route( "login@showForm" ) );
		}

        if( !Auth::user()->customer || !CustomerToUser::where( [ 'user_id' => Auth::id()  ] )->where( [ 'customer_id' => Auth::user()->custid ] )->first() ){
            Auth::logout();
            return redirect()->guest(route( "login@showForm" ) );
        }

		return $next( $r );
	}
}