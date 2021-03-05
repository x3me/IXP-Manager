<?php

namespace IXP\Http\Controllers\Api\V4;
/*
 * Copyright (C) 2009 - 2021 Internet Neutral Exchange Association Company Limited By Guarantee.
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
use Illuminate\Http\{
    JsonResponse,
    Request
};

use IXP\Models\{
    Aggregators\PatchPanelPortAggregator,
    PatchPanel
};

/**
 * PatchPanelController
 *
 * @author     Yann Robin <yann@islandbridgenetworks.ie>
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @category   APIv4
 * @package    IXP\Http\Controllers\Api\V4
 * @copyright  Copyright (C) 2009 - 2021 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class PatchPanelController extends Controller
{
    /**
     * Get the patch panel ports available for a patch panel
     *
     * @param   Request         $r      instance of the current HTTP request
     * @param   PatchPanel      $pp     the patch panel
     *
     * @return  JsonResponse
     */
    public function freePort( Request $r, PatchPanel $pp ): JsonResponse
    {
        return response()->json( [
            'ports' => PatchPanelPortAggregator::getAvailablePorts( $pp->id, [ $r->pppid ] )
        ]);
    }

    /**
     * Get the patch panel ports duplex available for a patch panel
     *
     * @param   Request         $r      instance of the current HTTP request
     * @param   PatchPanel      $pp     the patch panel
     *
     * @return  JsonResponse
     */
    public function freeDuplexPort( Request $r, PatchPanel $pp ): JsonResponse
    {
        return response()->json( [
            'ports' => PatchPanelPortAggregator::getAvailablePorts( $pp->id, [ $r->pppid ], null, false )
        ]);
    }
}